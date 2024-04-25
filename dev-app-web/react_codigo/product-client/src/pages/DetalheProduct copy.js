import { useEffect, useState } from "react"
import List from "@mui/material/List"
import ProductItem from "../components/ProductItem";
import axios from "axios";
import { getPassword, getUser } from "../helpers/Utils";
import ReactPaginate from "react-paginate";
import ArrowBackIosIcon from '@mui/icons-material/ArrowBackIos';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import '../styles/Home.css';

function Items({ currentItems }) {
    return (
        <div style={{margin:"auto"}}>
            <List sx={{ width: '100%', maxWidth: 360, bgcolor: 'background.paper' }}>
                {currentItems && currentItems.map((item) => (
                    <ProductItem item={item} />
                ))}
                
            </List>
        </div>
    );
}

export default function DetalheProduct({itensPerPage}) {
    

    const [currentItems, setCurrentItems] = useState(null);
    const [pageCount, setPageCount] = useState(0);
    


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
           // window.alert("DETALHE do produto: \n" + response.data["nome"]);
            console.log(response.data);
        
            if(response.data["sucesso"] == 1) {                     
                setCurrentItems(response.data);                  
            }
            else {               
                window.alert("Erro ao obter DETALHE do produto: \n" + response.data["erro"]);
            }
        })
    }, [0, 1]);


    return(
        <div style={{
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'center',
            padding: 0,
            boxSizing: 'border-box',
            width: '100%',
            height: '100%',
          }}>
          
            <Items currentItems={[currentItems]} />
            
        </div>
    );
}