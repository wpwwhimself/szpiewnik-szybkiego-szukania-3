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

interface OriginalKey {
    key: string | null,
    sheet_music_variants: string[],
}

export function SongRender({song, title, forceLyricsVariant}: SRProps){
    const [songSong, setSongSong] = useState<SongProps>()
    const [transposerOn, setTransposerOn] = useState(false)
    const [transposerActive, setTransposerActive] = useState(false)
    const [originalKey, setOriginalKey] = useState<OriginalKey>()

    useEffect(() => {
        if(title && !song){
            axios.get("/api/song-data", {params: {title_slug: slugAndDePL(title)}})
                .then(res => {
                    setSongSong(res.data.song)
                    setOriginalKey({
                        key: res.data.song.key || null,
                        sheet_music_variants: res.data.song.sheet_music_variants || [],
                    })
                })
        }else{
            setSongSong(song)
            setOriginalKey({
                key: song?.key || null,
                sheet_music_variants: song?.sheet_music_variants || [],
            })
        }
    }, [song])

    const transpose = (direction: "up" | "down") => {
        const processed = songSong?.sheet_music_variants
            .map(notes => (direction == "up" ? Hoch(notes) : Runter(notes)) as string)
        const newKey = direction == "up" ? Hoch(songSong?.key) : Runter(songSong?.key)
        setSongSong({
            ...songSong,
            key: newKey,
            sheet_music_variants: processed,
        })
        setTransposerActive(true)
    }
    const restore = () => {
        setSongSong({
            ...songSong,
            key: originalKey?.key,
            sheet_music_variants: originalKey?.sheet_music_variants,
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
        <div>
            {songSong?.sheet_music_variants && <SheetMusicRender notes={songSong.sheet_music_variants} />}
            {transposerOn && <div className="transposer-panel flex-right center wrap">
                <Button className="slick" onClick={() => transpose("up")}>+</Button>
                <Button className="slick" onClick={() => transpose("down")}>-</Button>
                {/* <Button className="slick" onClick={() => {}}>♯/♭</Button> */}
                <Button className="slick" onClick={() => restore()}>↺</Button>
                <Button onClick={() => setTransposerOn(false)}>OK</Button>
            </div>}
            {songSong?.lyrics && <SongLyrics lyrics={songSong.lyrics_variants} forceLyricsVariant={forceLyricsVariant} />}
        </div>
        <div className="flex-right center wrap">
            {songSong && <Button onClick={() => window.open(`/songs/show/${slugAndDePL(songSong.title)}`, "_blank")?.focus()}>Edytuj pieśń</Button>}
        </div>
    </>)
}
