import { Logo } from "./Logo"

interface Props {
    title: string,
}

export const Header = ({title}: Props) => {
    const APP_NAME = "Szpiewnik Szybkiego Szukania"

    return <header>
        <Logo />
        <div className="titles">
            <h1 id="page-title">{title || APP_NAME}</h1>
            {title && <h2>{APP_NAME}</h2>}
        </div>
        <nav className="flex-down but-mobile-right">
            <a href="{{ route('sets') }}">Zestawy</a>
        {true && <>
            <a href="{{ route('songs') }}">Pieśni</a>
            <a href="{{ route('places') }}">Miejsca</a>
            <a href="{{ route('ordinarium') }}">Części stałe</a>
            <a href="{{ route('formulas') }}">Formuły</a>
        </>}
        </nav>
    </header>
}
