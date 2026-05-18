const fs = require('fs');
const path = require('path');

const walkSync = function(dir, filelist) {
  const files = fs.readdirSync(dir);
  filelist = filelist || [];
  files.forEach(function(file) {
    if (fs.statSync(path.join(dir, file)).isDirectory()) {
      filelist = walkSync(path.join(dir, file), filelist);
    }
    else {
      filelist.push(path.join(dir, file));
    }
  });
  return filelist;
};

const frontendDir = path.join(__dirname, 'frontend', 'src', 'app');
const files = walkSync(frontendDir);

files.forEach(file => {
  let content = fs.readFileSync(file, 'utf8');
  let originalContent = content;

  if (file.endsWith('app.component.ts')) {
    content = content.replace(/styleUrls:\s*\["\.\/app\.component\.css"\]/g, '// styleUrls: ["./app.component.css"]');
  }

  if (file.endsWith('.html')) {
    content = content.replace(/\[p-disabled\]="true"/g, 'p-disabled="true"');
    content = content.replace(/\[p-required\]="true"/g, 'p-required="true"');
    content = content.replace(/\[p-container\]="true"/g, 'p-container="true"');
    
    // Expressão mais abrangente para substituir po-actions
    content = content.replace(/<po-actions[\s\S]*?label:\s*['"]([^'"]+)['"][\s\S]*?action:\s*([a-zA-Z0-9_]+)[\s\S]*?<\/po-actions>/g, '<po-button p-label="$1" (p-click)="$2()"></po-button>');
    content = content.replace(/<po-actions[^>]*>/g, '');
    content = content.replace(/<\/po-actions>/g, '');

    content = content.replace(/\[\(ngModel\)\]="([^"]+)"/g, '[ngModel]="$1" (ngModelChange)="$1 = \\$event"');
  }

  if (file.endsWith('.ts')) {
    content = content.replace(/\.poNotification\.info\(/g, '.poNotification.information(');
    content = content.replace(/format:\s*\(v:\s*any\)\s*=>\s*v\?\.nome/g, ''); // apenas remove o format que causa o erro
  }

  if (content !== originalContent) {
    fs.writeFileSync(file, content, 'utf8');
    console.log(`✅ Arquivo corrigido: ${file}`);
  }
});

console.log('\n🚀 Todas as correções foram aplicadas com sucesso!');
