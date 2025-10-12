import axios from "axios";
import { slugAndDePL } from "../helpers";
import { SongNote, SongProps } from "../types";
import { Button, DummyInput } from "./Interactives";
import { SongLyrics } from "./MassElements";
import { SheetMusicRender } from "./SheetMusicRender";
import React, { useState, useEffect } from "react";

interface SRProps{
    song?: SongProps,
    title?: string,
    forceLyricsVariant?: number,
    dontHideEditBtns?: boolean,
}

interface OriginalKey {
    key: string | null,
    sheet_music_variants: string[],
}

export function SongRender({song, title, forceLyricsVariant, dontHideEditBtns = false}: SRProps){
    const [songSong, setSongSong] = useState<SongProps>()
    const [transposerOn, setTransposerOn] = useState(false)
    const [transposer, setTransposer] = useState(0)
    const [noteForCurrentUser, setNoteForCurrentUser] = useState({} as SongNote)

    useEffect(() => {
        if(title && !song){
            axios.get("/api/song-data", {params: {title_slug: slugAndDePL(title)}})
                .then(res => {
                    setSongSong(res.data.song)
                    setNoteForCurrentUser(res.data.song?.notes.find((n: SongNote) =>
                        // @ts-ignore, ta zmienna istnieje w `resources/views/layout.blade.php`
                        n.user_id == user_id
                        && n.title == res.data.song.title
                    ));
                })
        }else{
            setSongSong(song)
            setNoteForCurrentUser(song?.notes?.find((n: SongNote) =>
                // @ts-ignore, ta zmienna istnieje w `resources/views/layout.blade.php`
                n.user_id == user_id
                && n.title == song.title
            ) || {} as SongNote);
        }
    }, [song])

    const transpose = (direction: "up" | "down") => {
        // @ts-ignore
        setTransposer(transposer + (direction == "up" ? 1 : -1));
    }
    const restore = () => {
        setTransposer(0);
    }

    return(<>
        <div className="flex-right center wrap">
            {songSong ?
            <>
                <DummyInput label="Tonacja" value={songSong.key + (
                    transposer != 0
                        ? (transposer > 0 ? " +" : " ") + transposer.toString()
                        : ""
                )} />
                <Button className={transposerOn ? "accent-border" : ""} onClick={() => setTransposerOn(!transposerOn)}>T</Button>
                <DummyInput label="Kategoria" value={songSong.category_desc} />
                <DummyInput label="Numer w śpiewniku Preis" value={songSong.number_preis} />
            </>
            :
                <span>Pieśń niezapisana</span>
            }
        </div>
        {noteForCurrentUser && <div className="notes ghost">{noteForCurrentUser.content}</div>}
        <div className="notes-and-lyrics-container">
            <div>
                {songSong?.sheet_music_variants && <SheetMusicRender notes={songSong.sheet_music_variants} transpose={transposer} />}
                {transposerOn && <div className="transposer-panel flex-right center wrap">
                    <label>Transponuj:</label>
                    <Button onClick={() => transpose("up")}>+</Button>
                    <Button onClick={() => transpose("down")}>-</Button>
                    {/* <Button onClick={() => {}}>♯/♭</Button> */}
                    <Button onClick={() => restore()}>↺</Button>
                    <Button onClick={() => setTransposerOn(false)}>OK</Button>
                </div>}
            </div>
            <div>
                {songSong?.lyrics && <SongLyrics lyrics={songSong.lyrics_variants} forceLyricsVariant={forceLyricsVariant} />}
            </div>
        </div>
        <div className={`${!dontHideEditBtns ? "show-after-click" : ""} flex-right center wrap`}>
            {songSong && <>
                <Button onClick={() => window.open(`/songs/show/${slugAndDePL(songSong.title)}`, "_blank")?.focus()}>Edytuj pieśń</Button>
                <Button onClick={() => window.open(`/songs/export/opensong/${slugAndDePL(songSong.title)}`)}>Pobierz OpenSong</Button>
            </>}
        </div>
    </>)
}
