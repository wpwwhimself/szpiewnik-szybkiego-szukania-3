import { useState, useEffect } from "react";
import { SongProps } from "../types";
import axios from "axios";
import { MassElemSection, SongLyrics } from "../../resources/js/components/MassElements";
import { DummyInput } from "../../resources/js/components/Interactives";
import { SheetMusicRender } from "../../resources/js/components/SheetMusicRender";
import { SongRender } from "../../resources/js/components/SongRender";

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
            <SongRender song={song} />
        </MassElemSection>
    );
}
