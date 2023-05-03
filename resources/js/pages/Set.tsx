import React, { useEffect } from "react";
import { createContext, useState } from "react";
import { massOrder, baseFormula } from "../helpers";
import { Button, Input, Select } from "../components/Interactives";
import { AddCollectorProps, AdderFilterProps, Extra, Formula, HandleAddCollectorProps, MModProps, MassElem, OrdinariumColorProps, OrdinariumProps, SelectOption, Set, SongCategoryProps, SongProps } from "../types";
import { ExtrasProcessor, MassElemSection, OrdinariumProcessor, PsalmLyrics, SongLyrics } from "../components/MassElements";
import { SheetMusicRender } from "../components/SheetMusicRender";
import axios from "axios";
import moment from "moment";

export const MModContext = createContext({} as MModProps);

export function MassSet(){
    const set_id = +window.location.href.replace(/.*\/(\d+)/, "$1");

    const [set, setSet] = useState({} as Set);
    const [ordinarium, setOrdinarium] = useState([] as OrdinariumProps[]);
    const [ordinarius_colors, setOrdColors] = useState([] as OrdinariumColorProps[]);
    const [formula, setFormula] = useState({} as Formula);
    const [songs, setSongs] = useState([] as SongProps[]);
    const [categories, setCategories] = useState([] as SongCategoryProps[]);
    const [adderFilters, setAdderFilters] = useState({categories: [1], preferences: [0,1,2,3,4]} as AdderFilterProps);

    const [addCollector, setAddCollector] = useState({song: undefined, before: undefined} as AddCollectorProps);

    useEffect(() => {
        axios.get("/api/set-data", {params: {set_id: set_id}}).then(res => {
            setSet(res.data.set);
            setOrdinarium(res.data.ordinarium);
            setOrdColors(res.data.ordinarius_colors);
            setFormula(res.data.formula);
            setSongs(res.data.songs);
            setCategories(res.data.categories);
        });
    }, []);

    // loader
    if(songs.length === 0){
        return <h2>Wczytuję...</h2>;
    }

    const ordColorOptions: SelectOption[] = [];
    ordinarius_colors.forEach(color => ordColorOptions.push({ value: color.name, label: color.display_name }));
    const handleColorChange = (ev: React.ChangeEvent<HTMLSelectElement>) => {
        setSet({ ...set, color: ev.target.value });
    }
    const thisMassOrdinarium = ordinarium.filter(el => el.color_code === set.color);

    // This mass' order
    let thisMassOrder: MassElem[] = [];
    massOrder.map(el => thisMassOrder.push({
        code: el.value as string,
        label: el.label,
        content: set[el.value as keyof Set] as string,
    }));

    //splitting songs
    thisMassOrder.filter(el => ["sIntro", "sOffer", "sCommunion", "sAdoration", "sDismissal"].includes(el.code)).forEach(el => {
        el.content?.split(/\r?\n/).forEach((title, i) => {
            thisMassOrder.splice(
                thisMassOrder.indexOf(el),
                0,
                { code: `${el.code}${i > 0 ? i : "" }`, label: el.label, content: title }
            );
        });
        thisMassOrder.splice(thisMassOrder.indexOf(el), 1);
    });

    //modifications
    const insertExtras = (extra: Extra, massOrder: MassElem[]) => {
        const pre = massOrder.filter(el2 => el2.code === extra.before)[0];
        let code = (extra.name.charAt(0) === "x") ? extra.name : "sB4"+extra.before;
        const same_code_count = thisMassOrder.filter(el => el.code.match(code)).length;
        if(same_code_count > 0){
            code += same_code_count
        }
        const content = (extra.name.charAt(0) === "x") ? undefined : extra.name;

        const addition = {
            code: code,
            label: `Zanim nastąpi ${pre?.label}`,
            content: content
        };

        if(pre) thisMassOrder.splice(
            thisMassOrder.indexOf(pre),
            (extra.replace) ? 1 : 0,
            addition
        );
        else thisMassOrder.push({ code: `sOutro`, label: `Dodatkowo`, content: extra.name });
    }

    if(!formula.gloria_present) thisMassOrder = thisMassOrder.filter(el => el.code !== "oGloria");
    formula.extras?.forEach((el) => {
        insertExtras(el, thisMassOrder);
    });
    set.extras?.forEach((el) => {
        insertExtras(el, thisMassOrder);
    });

    if(set.thisMassOrder === undefined) setSet({...set, thisMassOrder: thisMassOrder});

    const Mass = set.thisMassOrder?.map<React.ReactNode>((el, i) => {
        switch(el.code.charAt(0)){
            case "s": // song
            const song = songs.filter(s => s.title === el.content)[0];
            return(
                <MassElemSection id={el.code} key={i}>
                    <div className="songMeta">
                        <h2>{el.label}</h2>
                        <h1>{el.content}</h1>
                        <div className="flex-right center wrap">
                        {song ?
                          <>
                            <Input type="text" name="" label="Tonacja" value={song.key} disabled />
                            <Input type="text" name="" label="Kategoria" value={song.category_desc} disabled />
                            <Input type="text" name="" label="Numer w śpiewniku Preis" value={song.number_preis} disabled />
                          </>
                          :
                          <p>Pieśń niezapisana</p>
                        }
                        </div>

                        {song?.sheet_music && <SheetMusicRender notes={song.sheet_music} />}
                        {song?.lyrics && <SongLyrics lyrics={song.lyrics} />}
                    </div>
                </MassElemSection>
            )
        case "p": // psalm
            const part = thisMassOrdinarium.filter(el2 => el2.part === el.label.toLocaleLowerCase())[0];
            const formulaPart = ordinarium
                .filter(el2 => el2.color_code === baseFormula(formula.name))
                .filter(el2 => el2.part === el.label.toLocaleLowerCase())[0];
            const isNotWielkiPostAklamacja = !(baseFormula(set.formula) === "Wielki Post" && el.code === "pAccl");
            return(
                <MassElemSection id={el.code} key={i}>
                    <h1>{el.label}</h1>
                    {isNotWielkiPostAklamacja && <SheetMusicRender notes={part.sheet_music} />}
                    {formulaPart && <SheetMusicRender notes={formulaPart.sheet_music} />}
                    <PsalmLyrics lyrics={el.content!} />
                </MassElemSection>
            )
        case "o": // ordinarius
            return(
                <MassElemSection id={el.code} key={i}>
                    <OrdinariumProcessor code={el.code} colorCode={set.color} />
                </MassElemSection>
            )
        default:
            return(
                <MassElemSection id={el.code} key={i}>
                    <ExtrasProcessor elem={el} />
                </MassElemSection>
            )
        }
    });

    //deleting
    const MMod: MModProps = {
        addMassElem: (id) => {
            addModeOn(id);
        },
        eraseMassElem: (id) => {
            if(confirm(`Czy na pewno chcesz usunąć ten element mszy?`)){
              thisMassOrder = set.thisMassOrder!.filter(el => el.code !== id);
              setSet({...set, thisMassOrder: thisMassOrder});
            }
        }
    }
    document.addEventListener("click", (ev) => {
        document.querySelectorAll(".massElemEditorElement").forEach((el) => el.classList.remove("show"));
        (ev.target as HTMLElement).closest("section")?.querySelectorAll(".massElemEditorElement").forEach(el => el.classList.add("show"));
    });

    //adding
    function addModeOn(id?: string, useCollector: boolean = false){
        if(useCollector){
            const newMassOrder = thisMassOrder;
            insertExtras(
                {
                    name: addCollector.song,
                    before: addCollector.before,
                    replace: false
                } as Extra,
                newMassOrder
            );
            setSet({...set, thisMassOrder: newMassOrder});
        }
        setAddCollector({ before: id } as AddCollectorProps);
        document.getElementById("adder")!.classList.toggle("show");
    }

    function toggleFilters(category: number, preference: boolean = false){
        if(preference){
            const position = adderFilters.preferences.indexOf(category);
            if(position === -1){ //add to filters
                setAdderFilters({ ...adderFilters, preferences: [...adderFilters.preferences, category] });
            }else{ //delete from filters
                adderFilters.preferences.splice(position, 1);
                setAdderFilters({ ...adderFilters, preferences: adderFilters.preferences });
            }
        }else{
            const position = adderFilters.categories.indexOf(category);
            if(position === -1){ //add to filters
                setAdderFilters({ ...adderFilters, categories: [...adderFilters.categories, category] });
            }else{ //delete from filters
                adderFilters.categories.splice(position, 1);
                setAdderFilters({ ...adderFilters, categories: adderFilters.categories });
            }
        }
    }
    const handleAddCollector: HandleAddCollectorProps = (updatingField, value) => {
        setAddCollector({...addCollector, [updatingField]: value});
    }

    //jumping
    function jumperOn(){
        document.getElementById("jumper")!.classList.toggle("show");
    }

    // Mass' summary
    const summary = set.thisMassOrder
        ?.filter(el => el.content !== undefined)
        .filter(el => el.code !== "pAccl");

    return(<>
        <div className="flex-right center wrap settings">
            <Select name="color" label="Kolor cz.st." options={ordColorOptions} value={set.color} onChange={handleColorChange}/>
            <Button onClick={() => jumperOn()}>»</Button>
            <Button onClick={() => addModeOn("END")}>+</Button>
        </div>

        <div id="jumper" className="modal">
            <h1>Przejdź do</h1>
            <div className="grid-2">
                <div className="flex-right center wrap">
                {thisMassOrder
                    .filter(el => el.code.charAt(0) === "s")
                    .map((el, i) =>
                    <Button key={i}
                        onClick={() => {
                            jumperOn();
                            document.getElementById(el.code)?.scrollIntoView({behavior: "smooth", block: "center"});
                        }}
                        >
                        {el.label}
                    </Button>
                )}
                </div>
                <div className="flex-right center wrap">
                {thisMassOrder
                    .filter(el => el.code.charAt(0) !== "s")
                    .map((el, i) =>
                    <Button key={i}
                        onClick={() => {
                            jumperOn();
                            document.getElementById(el.code)?.scrollIntoView({behavior: "smooth", block: "center"});
                        }}
                        >
                        {el.label}
                    </Button>
                )}
                </div>
            </div>
            <div className="flex-right stretch">
                <Button onClick={() => jumperOn()}>Anuluj</Button>
            </div>
        </div>

        <div id="adder" className="modal">
            <h1>
                Dodaj pieśń
                {addCollector.before !== "END"
                    ? ` przed: ${thisMassOrder.filter(el => el.code === addCollector.before)[0]?.label}`
                    : " na koniec zestawu"}
            </h1>
            <div id="filters" className="grid-2">
                <div className="flex-right center wrap">
                {categories.map((el, i) =>
                    <Button key={i}
                        onClick={() => toggleFilters(el.id)}
                        className={adderFilters.categories.includes(el.id) ? "accent-border" : ""}
                        >
                        {el.name}
                    </Button>
                )}
                </div>
                <div className="flex-right center wrap">
                {["Wejście", "Dary", "Komunia", "Uwielbienie", "Zakończenie"]
                .map((el, i, ar) =>
                    <Button key={i}
                        onClick={() => toggleFilters(ar.indexOf(el), true)}
                        className={adderFilters.preferences.includes(ar.indexOf(el)) ? "accent-border" : ""}
                        >
                        {el}
                    </Button>
                )}
                </div>
            </div>
            <div id="song-list" className="flex-right center wrap">
            {songs.filter(el => adderFilters.categories.includes(el.song_category_id))
                .filter(el => {
                    if(adderFilters.preferences.length === 0){
                        if(el.preferences.substring(0, 9) === "0/0/0/0/0") return true;
                    }else for(let wanted_pref of adderFilters.preferences){
                        if(el.preferences.split("/")[wanted_pref] == "1") return true;
                    }
                    return false;
                })
                .map((song, i) =>
                <Button key={i}
                    onClick={() => handleAddCollector("song", song.title)}
                    className={`light-button ${addCollector.song === song.title && "accent-border"}`}
                    >
                    {song.title}
                </Button>
            )}
            </div>
            <div className="flex-right stretch">
                <Button onClick={() => addModeOn()}>Anuluj</Button>
                {addCollector.song && addCollector.before && <Button onClick={() => addModeOn(undefined, true)}>Dodaj</Button>}
            </div>
        </div>

        <div className="flex-down">
            <MModContext.Provider value={MMod}>
                <MassElemSection id="summary" uneresable>
                    <h1>Skrót</h1>
                    <div className="grid-2">
                        <div>
                            <h2>Meta</h2>
                            <div className="flex-right wrap center">
                                <Input type="text" name="" label="Formuła" disabled value={set.formula} />
                                <Input type="text" name="" label="Utworzony" disabled value={moment(set.created_at).format("DD.MM.YYYY")} />
                                <Input type="text" name="" label="Zmodyfikowany" disabled value={moment(set.updated_at).format("DD.MM.YYYY")} />
                            </div>
                        </div>
                        <div>
                            <h2>Pieśni i psalm</h2>
                            <ol className="summary">
                            {summary?.map((el, i) =>
                                <li key={i}>
                                    <span>{
                                        (el.content!.indexOf("\n") > -1) ?
                                        el.content!.substring(0, el.content!.indexOf("\n")) :
                                        el.content
                                    }</span>
                                    <span className="ghost">{el.label}</span>
                                </li>
                            )}
                            </ol>
                        </div>
                    </div>
                </MassElemSection>
                {Mass}
            </MModContext.Provider>
        </div>
    </>)
}
