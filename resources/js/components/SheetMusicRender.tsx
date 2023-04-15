import Abcjs from "react-abcjs"

interface SMRProps{
    notes: string | null,
}

export function SheetMusicRender({notes}: SMRProps){
    const engraverParams = {
        responsive: "resize",
        editable: false,
        paddingbottom: 15,
        paddingright: 15,
    }
    const renderParams = {
        viewportHorizontal: true
    }

    return(
        <div className="flex-right center sheet-container">
            <Abcjs
                abcNotation={notes ?? ""}
                engraverParams={engraverParams}
                renderParams={renderParams}
                />
        </div>
    )
}
