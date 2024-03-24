import { Logo } from "./Logo"
import moment from "moment"

export const Header = () => {
    const APP_NAME = "Szpiewnik Szybkiego Szukania"

    return <footer>
        <div className="auth flex-down">
        {true ? <>
            <span>Zalogowany jako <strong>{true}</strong></span>
            <a href="#/">Wyloguj</a>
        </> : <>
            <a href="">Zaloguj się</a>
        </>}
        </div>
        <div className="titles">
            <h2>{APP_NAME}</h2>
            <p>Projekt i wykonanie: <a href="http://wpww.pl/">Wojciech Przybyła</a></p>
            <p>&copy; 2023 - {moment().format("Y")}</p>
        </div>
        <Logo />
    </footer>
}
