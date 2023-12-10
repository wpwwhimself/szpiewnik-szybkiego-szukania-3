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
}

export function SongRender({song, title}: SRProps){
    const [songSong, setSongSong] = useState<SongProps>()

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
                <DummyInput label="Kategoria" value={songSong.category_desc} />
                <DummyInput label="Numer w śpiewniku Preis" value={songSong.number_preis} />
                <Button onClick={() => window.open(`/songs/show/${slugAndDePL(songSong.title)}`, "_blank")?.focus()}>Edytuj</Button>
            </>
            :
                <p>Pieśń niezapisana</p>
            }
        </div>
        <div>
            {songSong?.sheet_music_variants && <SheetMusicRender notes={songSong.sheet_music_variants} />}
            {songSong?.lyrics && <SongLyrics lyrics={songSong.lyrics} />}
        </div>
    </>)
}
