import { useState, useEffect } from "react";
import { SongProps } from "../types";
import axios from "axios";
import { MassElemSection, SongLyrics } from "../components/MassElements";
import { DummyInput } from "../components/Interactives";
import { SheetMusicRender } from "../components/SheetMusicRender";

export function PresentSong(){
    const title_slug = window.location.href.replace(/.*\/present\/(.*).*/, "$1");

    const [song, setSong] = useState({} as SongProps);

    useEffect(() => {
        axios.get("/api/song-data", {params: {
            title_slug: title_slug,
        }}).then(res => {
            setSong(res.data.song);
        });
    }, []);

    return(
        <MassElemSection id="song">
            <div className="songMeta">
                <h1>{song.title}</h1>
                <div className="flex-right center wrap">
                    {song ?
                    <>
                        <DummyInput label="Tonacja" value={song.key} />
                        <DummyInput label="Kategoria" value={song.category_desc} />
                        <DummyInput label="Numer w śpiewniku Preis" value={song.number_preis} />
                    </>
                    :
                        <p>Pieśń niezapisana</p>
                    }
                </div>

                {song?.sheet_music && <SheetMusicRender notes={song.sheet_music} />}
                {song?.lyrics && <SongLyrics lyrics={song.lyrics} />}
            </div>
        </MassElemSection>
    );
}
