import abcjs from "abcjs"
import { useEffect } from "react";

interface SMRProps{
    notes: string | null,
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

    function render(){
        const res = abcjs.renderAbc(
            "sheet-"+this_id,
            notes ?? "",
            {
                responsive: "resize",
                germanAlphabet: true,
            }
        );
    }

    useEffect(() => render(), [notes]);

    return(
        <div className="flex-right center sheet-container">
            <div id={`sheet-${this_id}`}></div>
        </div>
    )
}
