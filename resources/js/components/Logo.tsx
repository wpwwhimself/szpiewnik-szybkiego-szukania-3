import { Link } from "react-router-dom";

export function Logo(){
  return(
    <Link to="/">
      <img src="/sz3_olive.svg" alt="app's logo" className="logo" />
    </Link>
  );
}
