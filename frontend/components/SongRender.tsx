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

    return(<>
        <div className="flex right center wrap">
            {songSong ?
            <div className="flex right center middle nowrap">
                <div className="flex right center middle">
                    <DummyInput label="Tonacja" value={songSong.key} />
                </div>
                <DummyInput label="Kategoria" value={songSong.category_desc} />
                <DummyInput label="Numer w śpiewniku Preis" value={songSong.number_preis} />
            </div>
            :
                <span>Pieśń niezapisana</span>
            }
        </div>
        {noteForCurrentUser && <div className="notes ghost">{noteForCurrentUser.content}</div>}
        <div className="notes-and-lyrics-container">
            <div>
                {songSong?.sheet_music_variants && <SheetMusicRender notes={songSong.sheet_music_variants} />}
            </div>
            <div>
                {songSong?.lyrics && <SongLyrics lyrics={songSong.lyrics_variants} forceLyricsVariant={forceLyricsVariant} />}
            </div>
        </div>
        <div className={`${!dontHideEditBtns ? "show-after-click" : ""} flex right center wrap`}>
            {songSong && <>
                <Button className="primary" onClick={() => window.open(`/songs/show/${slugAndDePL(songSong.title)}`, "_blank")?.focus()}>Edytuj pieśń</Button>
                <Button onClick={() => window.open(`/songs/export/opensong/${slugAndDePL(songSong.title)}`)}>Pobierz OpenSong</Button>
            </>}
        </div>
    </>)
}
