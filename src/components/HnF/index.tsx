import "./style.css";
import { Logo } from "../Logo";
import { Link } from "react-router-dom";

declare interface HeaderProps{
  title: string,
};

export function Header({title}: HeaderProps){
  return(
    <header>
      <Logo />
      <div className="titles">
        <h1>{title}</h1>
        <h2>Szpiewnik Szybkiego Szukania 3</h2>
      </div>
      <nav className="flex-down">
        <Link to="/songs">Pieśni</Link>
        <Link to="/ordinarium">Części stałe</Link>
      </nav>
    </header>
  )
}

export function Footer(){
  const today = new Date();

  return(
    <footer>
      <Logo />
      <div className="titles">
        <h2>Szpiewnik Szybkiego Szukania 3</h2>
        <p>Projekt i wykonanie: <a href="http://wpww.pl/">Wojciech Przybyła</a></p>
        <p>&copy; 2023 - {today.getFullYear()}</p>
      </div>
    </footer>
  )
}