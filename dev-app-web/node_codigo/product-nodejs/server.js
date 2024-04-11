const app = require('./src/app');
const port = 3000;

app.listen(port, () => {
    console.log('Aplicacao Produto executando. http://localhost:' + port + "/api/");
})