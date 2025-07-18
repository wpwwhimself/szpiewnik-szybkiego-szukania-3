import abcjs from "abcjs"
import React, { useEffect, useState } from "react";
import { Button } from "./Interactives";
import moment from "moment";

interface SMRProps{
    notes: string | string[] | null,
}

export function SheetMusicRender({notes}: SMRProps){
    const this_id = Date.now() + Math.random();

    const engraverParams = {
        responsive: "resize",
        editable: false,
        paddingbottom: 15,
        paddingright: 15,
    }
    const renderParams = {
        viewportHorizontal: true
    }

    const render_variants = Array.isArray(notes) && notes.length > 1;
    const [variant, setVariant] = useState(
        Array.isArray(notes)
        ? moment().dayOfYear() % notes.length
        : 0
    );
    const notes_ready = Array.isArray(notes) ? notes[variant] ?? notes[0] : notes;

    function render(){
        const res = abcjs.renderAbc(
            "sheet-"+this_id,
            notes_ready ?? "",
            {
                responsive: "resize",
                // germanAlphabet: true, // zmienia B -> H w akordach, bezużyteczne
            }
        );
    }

    const changeVariant = (var_no: number) => {
        setVariant(var_no);
    }
    const randomizeVariant = () => {
        let new_variant;
        do {
            new_variant = Math.floor(Math.random() * (notes?.length || 1));
        } while (new_variant === variant);
        changeVariant(new_variant);
    }

    useEffect(() => render(), [notes_ready]);

    return(<>
        {render_variants &&
        <div className="flex-right center">
        {notes.map((var_notes, var_no) =>
            <Button key={var_no}
                className={[variant === var_no && 'accent-border', 'slick'].filter(Boolean).join(" ")}
                onClick={() => changeVariant(var_no)}>
                {var_no + 1}
            </Button>)}
            <Button onClick={randomizeVariant} title="Losowo">L</Button>
        </div>
        }
        <div className="flex-right center sheet-container">
            <div id={`sheet-${this_id}`}></div>
        </div>
    </>)
}
