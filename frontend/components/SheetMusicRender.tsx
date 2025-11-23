import abcjs from "abcjs"
import React, { useEffect, useState } from "react";
import { Button } from "./Interactives";
import moment from "moment";

interface SMRProps{
    notes: string | string[] | null,
    withoutTransposer?: boolean,
}

export function SheetMusicRender({notes, withoutTransposer = false}: SMRProps){
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
                jazzchords: true,
                germanAlphabet: true,
                visualTranspose: transposer || 0,
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

    const [transposerOn, setTransposerOn] = useState(false);
    const [transposer, setTransposer] = useState(0);
    const transpose = (direction: "up" | "down") => {
        setTransposer(transposer + (direction == "up" ? 1 : -1));
    }
    const restore = () => {
        setTransposer(0);
        setTransposerOn(false);
    }

    useEffect(() => render(), [notes_ready, transposer]);

    return(<>
        {(render_variants || !withoutTransposer) &&
        <div className="flex right center">
            {render_variants && <>
                {notes.map((var_notes, var_no) =>
                    <Button key={var_no}
                        className={[variant === var_no && 'accent-border', 'toggle'].filter(Boolean).join(" ")}
                        onClick={() => changeVariant(var_no)}>
                        {var_no + 1}
                    </Button>)}
                <Button onClick={randomizeVariant} title="Losowo">L</Button>
            </>}
            {!withoutTransposer && <>
                <Button className={(transposerOn || transposer != 0) ? "accent-border" : ""} onClick={() => setTransposerOn(!transposerOn)}>
                    T {(
                        transposer != 0
                            ? (transposer > 0 ? " +" : " ") + transposer.toString()
                            : ""
                    )}
                </Button>
                {transposerOn && <div className="transposer-panel flex right center middle wrap">
                    <Button onClick={() => transpose("up")}>+</Button>
                    <Button onClick={() => transpose("down")}>-</Button>
                    <Button onClick={() => restore()}>↺</Button>
                </div>}
            </>}
        </div>
        }
        <div className="sheet-container">
            <div id={`sheet-${this_id}`}></div>
        </div>
    </>)
}
