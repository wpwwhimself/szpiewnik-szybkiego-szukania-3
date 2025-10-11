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
    const [transposerActive, setTransposerActive] = useState(false)
    const [originalKey, setOriginalKey] = useState<OriginalKey>()
    const [noteForCurrentUser, setNoteForCurrentUser] = useState({} as SongNote)

    useEffect(() => {
        if(title && !song){
            axios.get("/api/song-data", {params: {title_slug: slugAndDePL(title)}})
                .then(res => {
                    setSongSong(res.data.song)
                    setOriginalKey({
                        key: res.data.song.key || null,
                        sheet_music_variants: res.data.song.sheet_music_variants || [],
                    })
                    setNoteForCurrentUser(res.data.song?.notes.find((n: SongNote) =>
                        // @ts-ignore, ta zmienna istnieje w `resources/views/layout.blade.php`
                        n.user_id == user_id
                        && n.title == res.data.song.title
                    ));
                })
        }else{
            setSongSong(song)
            setOriginalKey({
                key: song?.key || null,
                sheet_music_variants: song?.sheet_music_variants || [],
            })
            setNoteForCurrentUser(song?.notes?.find((n: SongNote) =>
                // @ts-ignore, ta zmienna istnieje w `resources/views/layout.blade.php`
                n.user_id == user_id
                && n.title == song.title
            ) || {} as SongNote);
        }
    }, [song])

    const transpose = (direction: "up" | "down") => {
        let processed = songSong?.sheet_music_variants
            // @ts-ignore
            ?.map(notes => (direction == "up" ? Hoch(notes) : Runter(notes)) as string)

        // @ts-ignore
        const newKey = direction == "up" ? Hoch(songSong?.key) : Runter(songSong?.key)
        if (songSong) setSongSong({
            ...songSong,
            key: newKey,
            sheet_music_variants: processed || [],
        })
        setTransposerActive(true)
    }
    const restore = () => {
        if (songSong) setSongSong({
            ...songSong,
            key: originalKey?.key || null,
            sheet_music_variants: originalKey?.sheet_music_variants || [],
        })
        setTransposerActive(false)
    }

    return(<>
        <div className="flex-right center wrap">
            {songSong ?
            <>
                <DummyInput label="Tonacja" value={songSong.key} />
                <Button className={transposerActive ? "accent-border" : ""} onClick={() => setTransposerOn(!transposerOn)}>T</Button>
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
                {songSong?.sheet_music_variants && <SheetMusicRender notes={songSong.sheet_music_variants} />}
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
