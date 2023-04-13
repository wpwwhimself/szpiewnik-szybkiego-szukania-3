import { Link } from "react-router-dom";
import style from "./style.module.css";

export function Logo(){
  return(
    <Link to="/">
      <img src="/sz3_olive.svg" alt="app's logo" className={style.logo} />
    </Link>
  );
}