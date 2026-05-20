import * as bcrypt from 'bcrypt';

async function test() {
  const hash = '$2y$10$z3sVUerxC7Vv.veIphApvuOeRnyOYNbZc5mrnN4xrwdfiEmBvQZQy';
  const pass = 'admin';
  const match = await bcrypt.compare(pass, hash);
  console.log('Match admin ($2y):', match);

  const hash2a = hash.replace('$2y$', '$2a$');
  const match2a = await bcrypt.compare(pass, hash2a);
  console.log('Match admin ($2a):', match2a);
}
test();
