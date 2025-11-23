import React, { useState, useEffect } from "react";
import { OrdinariumProps } from "../types";
import axios from "axios";
import { MassElemSection, OrdinariumProcessor, SongLyrics } from "../components/MassElements";
import { Button, DummyInput } from "../components/Interactives";
import { SheetMusicRender } from "../components/SheetMusicRender";

export function PresentOrdinarium(){
    const color = window.location.href.replace(/.*\/present\/(.*).*/, "$1");
    const dParts = {
      "kyrie": "Kyrie",
      "gloria": "Gloria",
      "psalm": "Psalm",
      "aklamacja": "Aklamacja",
      "sanctus": "Sanctus",
      "agnus-dei": "Agnus Dei",
    }

    const [ordinarium, setOrdinarium] = useState([] as OrdinariumProps[]);
    const [currentOrdinarius, setCurrentOrdinarius] = useState({} as OrdinariumProps);
    const changeCurrentOrdinarius = (new_ordinarius: OrdinariumProps) => {
        setCurrentOrdinarius(new_ordinarius);
    };

    useEffect(() => {
        axios.get("/api/ordinarius-data", {params: {
            color: color,
        }}).then(res => {
            setOrdinarium(res.data.ordinarium);
            setCurrentOrdinarius(res.data.ordinarium[0]);
        });
    }, []);

    return <div>
        <div className="flex right center">
            {ordinarium.map((el, key) =>
                <Button key={key}
                    className={[currentOrdinarius.part === el.part && 'accent-border', 'toggle'].filter(Boolean).join(" ")}
                    onClick={() => changeCurrentOrdinarius(el)}
                >
                    {dParts[el.part as keyof typeof dParts]}
                </Button>
            )}

        </div>

        <SheetMusicRender notes={currentOrdinarius.sheet_music} />
        <div className="flex right spread and-cover">
            <Button className="primary" onClick={() => {window.location.href = `/ordinarium/show/${currentOrdinarius.color_code}_${currentOrdinarius.part}`}}>Edytuj</Button>
        </div>
    </div>
}
