import { useEffect, useState } from "react"
import List from "@mui/material/List"
import ProductItem from "../components/ProductItem";
import axios from "axios";
import { getPassword, getUser } from "../helpers/Utils";
import ReactPaginate from "react-paginate";
import ArrowBackIosIcon from '@mui/icons-material/ArrowBackIos';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import '../styles/Home.css';



export default function DetalheProduct() {
      
    const [currentItemProduto, setCurrentItemProduto] = useState(null);
    

    useEffect( () => {
        axios({
            method: 'get',           
            url: 'https://productifes-dispmoveisbsi.b4a.run/pegar_detalhes_produto.php',
            params: {                 
                  id: 41
                             
            },
            auth: {
                username: getUser(),
                password: getPassword()
            },
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then((response) => {             
            window.alert("DETALHE do produto: " + 
             "\n" + response.data.nome  +
             "\n" + response.data.preco +
             "\n" + response.data.descricao +
             "\n" + response.data.criado_porme +
             "\n" + response.data.img
            );           
            
            if(response.data["sucesso"] == 1) {                  
                setCurrentItemProduto(response.data);   
                console.log(response.data)             
            }
            else {               
                window.alert("Erro ao obter DETALHE do produto: \n" + response.data["erro"]);
            }
          
        })
    }, [0, 1]);

    return (
        <div>
                  
         
                  
    
      
      
        </div>
      );
    };
    
 
