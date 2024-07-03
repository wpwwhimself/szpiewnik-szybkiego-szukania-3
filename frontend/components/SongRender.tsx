import axios from "axios";
import { slugAndDePL } from "../helpers";
import { SongProps } from "../types";
import { Button, DummyInput } from "./Interactives";
import { SongLyrics } from "./MassElements";
import { SheetMusicRender } from "./SheetMusicRender";
import { useState, useEffect } from "react";

interface SRProps{
    song?: SongProps,
    title?: string,
    forceLyricsVariant?: number,
}

export function SongRender({song, title, forceLyricsVariant}: SRProps){
    const [songSong, setSongSong] = useState<SongProps>()
    const [transposerOn, setTransposerOn] = useState(false)

    useEffect(() => {
        if(title && !song){
            axios.get("/api/song-data", {params: {title_slug: slugAndDePL(title)}})
                .then(res => { setSongSong(res.data.song) })
        }else{
            setSongSong(song)
        }
    }, [song])

    return(<>
        <div className="flex-right center wrap">
            {songSong ?
            <>
                <DummyInput label="Tonacja" value={songSong.key} />
                <Button onClick={() => setTransposerOn(!transposerOn)}>T</Button>
                <DummyInput label="Kategoria" value={songSong.category_desc} />
                <DummyInput label="Numer w śpiewniku Preis" value={songSong.number_preis} />
            </>
            :
                <span>Pieśń niezapisana</span>
            }
        </div>
        <div>
            {songSong?.sheet_music_variants && <SheetMusicRender notes={songSong.sheet_music_variants} />}
            {transposerOn && <div className="transposer-panel flex-right center wrap">
                <Button className="slick" onClick={() => {}}>+</Button>
                <Button className="slick" onClick={() => {}}>-</Button>
                <Button className="slick" onClick={() => {}}>♯/♭</Button>
                <Button className="slick" onClick={() => {}}>↺</Button>
                <Button onClick={() => setTransposerOn(false)}>OK</Button>
            </div>}
            {songSong?.lyrics && <SongLyrics lyrics={songSong.lyrics_variants} forceLyricsVariant={forceLyricsVariant} />}
        </div>
        <div className="flex-right center wrap">
            {songSong && <Button onClick={() => window.open(`/songs/show/${slugAndDePL(songSong.title)}`, "_blank")?.focus()}>Edytuj pieśń</Button>}
        </div>
    </>)
}
