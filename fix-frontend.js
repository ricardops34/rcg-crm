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

  // 1. Correção do app.component.css
  if (file.endsWith('app.component.ts')) {
    content = content.replace(/styleUrls:\s*\["\.\/app\.component\.css"\]/g, '// styleUrls: ["./app.component.css"]');
  }

  // 2. Correções nos templates HTML
  if (file.endsWith('.html')) {
    content = content.replace(/\[p-disabled\]="true"/g, 'p-disabled="true"');
    content = content.replace(/\[p-required\]="true"/g, 'p-required="true"');
    content = content.replace(/\[p-container\]="true"/g, 'p-container="true"');
    
    // Corrigir <po-actions> para <po-button>
    content = content.replace(/<po-actions\s+\[p-actions\]="\[\{\s*label:\s*'([^']+)',\s*action:\s*([^ }]+)\s*\}\]"\s*\/?>(?:<\/po-actions>)?/g, '<po-button p-label="$1" (p-click)="$2()"></po-button>');

    // Corrigir [(ngModel)] para [ngModel] e (ngModelChange) para evitar NG8007
    content = content.replace(/\[\(ngModel\)\]="([^"]+)"/g, '[ngModel]="$1" (ngModelChange)="$1 = \\$event"');
  }

  // 3. Correções nos arquivos TypeScript
  if (file.endsWith('.ts')) {
    // Corrigir poNotification.info para poNotification.information
    content = content.replace(/\.poNotification\.info\(/g, '.poNotification.information(');
    
    // Corrigir o format: (v: any) => v?.nome na tabela
    content = content.replace(/property:\s*"vendedor",\s*label:\s*"Vendedor",\s*format:\s*\(v:\s*any\)\s*=>\s*v\?\.nome/g, 'property: "vendedor.nome", label: "Vendedor"');
  }

  if (content !== originalContent) {
    fs.writeFileSync(file, content, 'utf8');
    console.log(`✅ Arquivo corrigido: ${file}`);
  }
});

console.log('\n🚀 Todas as correções foram aplicadas com sucesso!');
