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
  if (file.endsWith('.html')) {
    let content = fs.readFileSync(file, 'utf8');
    let originalContent = content;

    // Remove a barra invertida acidental do \$event
    content = content.replace(/\\\$event/g, '$event');

    if (content !== originalContent) {
      fs.writeFileSync(file, content, 'utf8');
      console.log(`✅ Corrigido o erro do $event no arquivo: ${file}`);
    }
  }
});
console.log('\n🚀 Feito! O evento do Angular foi ajustado.');
