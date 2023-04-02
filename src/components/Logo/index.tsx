import { Link } from "react-router-dom";
import "./style.css";

export function Logo(){
  return(
    <Link to="/">
      <img src="./sz3_olive.svg" alt="app's logo" className="logo" />
    </Link>
  );
}