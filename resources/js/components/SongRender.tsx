import { slugAndDePL } from "../helpers";
import { SongProps } from "../types";
import { Button, DummyInput } from "./Interactives";
import { SongLyrics } from "./MassElements";
import { SheetMusicRender } from "./SheetMusicRender";

interface SRProps{
    song?: SongProps,
}

export function SongRender({song}: SRProps){
    return(<>
        <div className="flex-right center wrap">
            {song ?
            <>
                <DummyInput label="Tonacja" value={song.key} />
                <DummyInput label="Kategoria" value={song.category_desc} />
                <DummyInput label="Numer w śpiewniku Preis" value={song.number_preis} />
                <Button onClick={() => window.open(`/songs/show/${slugAndDePL(song.title)}`, "_blank")?.focus()}>Edytuj</Button>
            </>
            :
                <p>Pieśń niezapisana</p>
            }
        </div>
        <div>
            {song?.sheet_music && <SheetMusicRender notes={song.sheet_music} />}
            {song?.lyrics && <SongLyrics lyrics={song.lyrics} />}
        </div>
    </>)
}
