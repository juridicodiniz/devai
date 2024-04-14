const db = require('../config/database');
const { ImgurClient } = require('imgur');
const dotenv = require('dotenv');
const { createReadStream } = require('fs');

exports.getAllProducts = async (req, res) => {
    if (req.query.hasOwnProperty('limit') && req.query.hasOwnProperty('offset')) {

        const { limit, offset } = req.query;
        try {
            const getAllProductsQuery = await db.query(
                "SELECT * FROM produtos order by id desc LIMIT $1 OFFSET $2 ",
                [limit, offset]
            );
            if (getAllProductsQuery.rows.length !== 0) {
                res.status(200).send(
                    {
                        sucesso: 1,
                        produtos: getAllProductsQuery.rows,
                        qtde_produtos: getAllProductsQuery.rows.length
                    }
                );
            }
        }
        catch (err) {
            var errorMsg = "erro BD: "; -
                res.status(200).send(
                    {
                        sucesso: 0,
                        cod_erro: 2,
                        erro: errorMsg.concat(err)
                    }
                );
        }
    }
    else {
        var errorMsg = "faltam parametros";
        res.status(200).send(
            {
                sucesso: 0,
                cod_erro: 3,
                erro: errorMsg
            }
        );
    }
};


exports.getUserProducts = async (req, res) => {
    if (req.query.hasOwnProperty('limit') && req.query.hasOwnProperty('offset')) {

        const { limit, offset } = req.query;
        try {
            const getAllProductsQuery = await db.query(
                "SELECT * FROM produtos  WHERE usuarios_login = '" + req.auth.user + "' LIMIT $1 OFFSET $2",
                [limit, offset]
            );
            if (getAllProductsQuery.rows.length !== 0) {
                res.status(200).send(
                    {
                        sucesso: 1,
                        produtos: getAllProductsQuery.rows,
                        qtde_produtos: getAllProductsQuery.rows.length
                    }
                );
            }
        }
        catch (err) {
            var errorMsg = "erro BD: "; -
                res.status(200).send(
                    {
                        sucesso: 0,
                        cod_erro: 2,
                        erro: errorMsg.concat(err)
                    }
                );
        }
    }
    else {
        var errorMsg = "faltam parametros";
        res.status(200).send(
            {
                sucesso: 0,
                cod_erro: 3,
                erro: errorMsg
            }
        );
    }
};




exports.addProduct = async (req, res) => {
    if ('nome' in req.body && 'preco' in req.body && 'descricao' in req.body
        && req.hasOwnProperty('file')) {
        const { nome, preco, descricao } = req.body;

        const imgurClient = new ImgurClient({ clientId: process.env.IMGUR_CLIENT_ID });
        const imgurRes = await imgurClient.upload(
            {
                image: createReadStream(req.file.path),
                type: 'stream'
            }
        );
        if (imgurRes.status === 200) {
            try {
                const addProductQuery = await db.query(
                    "INSERT INTO produtos(nome, preco, descricao, img, usuarios_login) VALUES($1, $2, $3, $4, $5)",
                    [nome, preco, descricao, imgurRes.data.link, req.auth.user]
                );
                res.status(200).send(
                    {
                        sucesso: 1
                    }
                );
            }
            catch (err) {
                var erroMsg = "erro BD: ";
                res.status(200).send(
                    {
                        sucesso: 0,
                        cod_erro: 2,
                        erro: erroMsg.concat(err)
                    }
                );
            }
        }
        else {
            res.status(200).send(
                {
                    sucesso: 0,
                    cod_erro: 2,
                    erro: "erro IMGUR: falha ao subir imagem para o IMGUR"
                }
            );
        }
    }
    else {
        var erroMsg = "faltam parametros";
        res.status(200).send(
            {
                sucesso: 0,
                cod_erro: 3,
                erro: erroMsg
            }
        );
    }
};




exports.getDetalhesProduct = async (req, res) => {
    if (req.query.hasOwnProperty('id')) {

        const id_produto = req.query.id;

        try {
            const getDetalhesProductsQuery = await db.query(
                "SELECT * FROM produtos WHERE id =  $1",
                [id_produto]
            );
            if (getDetalhesProductsQuery.rows.length !== 0) {
                res.status(200).send(
                    {
                        sucesso: 1,
                        nome: getDetalhesProductsQuery.rows[0].nome,
                        preco: getDetalhesProductsQuery.rows[0].preco,
                        descricao: getDetalhesProductsQuery.rows[0].descricao,
                        criado_por: getDetalhesProductsQuery.rows[0].usuarios_login,
                        criado_em: getDetalhesProductsQuery.rows[0].criado_em,
                        img: getDetalhesProductsQuery.rows[0].img

                    }
                );
            }
        }
        catch (err) {
            var errorMsg = "erro BD: "; -
                res.status(200).send(
                    {
                        sucesso: 0,
                        cod_erro: 2,
                        erro: errorMsg.concat(err)
                    }
                );
        }
    }
    else {
        var errorMsg = "faltam parametros";
        res.status(200).send(
            {
                sucesso: 0,
                cod_erro: 3,
                erro: errorMsg
            }
        );
    }
};


exports.UpdateProduct = async (req, res) => {
    /*
    Parâmetros de entrada:
    ● id -> indica o id do produto a ser atualizado.
    ● novo_nome -> indica o novo nome que o produto terá.
    ● novo_preco -> indica o novo preco que o produto terá.
    ● nova_descricao -> indica a nova descrição que o produto terá.
    ● nova_img -> indica a nova imagem que o produto terá.  
          
    */  


    if ('id' in req.body && 'novo_nome' in req.body && 'novo_preco' in req.body && 'nova_descricao' in req.body  && req.hasOwnProperty('file')) 
    {
        const { id, novo_nome, novo_preco, nova_descricao } = req.body;

        const imgurClient = new ImgurClient({ clientId: process.env.IMGUR_CLIENT_ID });
        const imgurRes = await imgurClient.upload(
            {
                image: createReadStream(req.file.path),
                type: 'stream'
            }
        );
        if (imgurRes.status === 200) {
            try {


                const addProductQuery = await db.query(
                    "UPDATE  produtos SET nome=$1, preco = $2, descricao =$3, img=$4 WHERE usuarios_login = $5 and id = $6",
                    [novo_nome, novo_preco, nova_descricao, imgurRes.data.link, req.auth.user, id]
                );

                res.status(200).send(
                    {
                        sucesso: 1
                    }
                );
            }
            catch (err) {
                var erroMsg = "erro BD: ";
                res.status(200).send(
                    {
                        sucesso: 0,
                        cod_erro: 2,
                        erro: erroMsg.concat(err)
                    }
                );
            }
        }
        else {
            res.status(200).send(
                {
                    sucesso: 0,
                    cod_erro: 2,
                    erro: "erro IMGUR: falha ao subir imagem para o IMGUR"
                }
            );
        }
    }
    else {
        var erroMsg = "faltam parametros";
        res.status(200).send(
            {
                sucesso: 0,
                cod_erro: 3,
                erro: erroMsg
            }
        );
    }
};

exports.deleteProduct = async (req, res) => {
    /*
    Parâmetros de entrada:
    ● id -> indica o id do produto a ser excluido.
             
    */
    

    if ('id' in req.body) {
        const { id } = req.body;
              
        try {
            const addProductQuery = await db.query(
                "DELETE FROM produtos WHERE (usuarios_login = $1 and id = $2 )",               
                [ req.auth.user, id]
            );

    
            res.status(200).send(
                {
                    sucesso: 1
                }
            );
        }
        catch (err) {
            var erroMsg = "erro BD: ";
            res.status(200).send(
                {
                    sucesso: 0,
                    cod_erro: 2,
                    erro: erroMsg.concat(err)
                }
            );
        }
    }
    else {
        var erroMsg = "faltam parametros";
        res.status(200).send(
            {
                sucesso: 0,
                cod_erro: 3,
                erro: erroMsg
            }
        );
    }
};