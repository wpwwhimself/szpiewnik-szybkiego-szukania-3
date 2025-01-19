import React, { createContext, useState, useEffect } from "react";
import { massOrder, baseFormula, slugAndDePL } from "../helpers";
import { Button, DummyInput, Select } from "../components/Interactives";
import { AddCollectorProps, AdderFilterProps, Extra, Formula, HandleAddCollectorProps, MModProps, MassElem, OrdinariumColorProps, OrdinariumProps, PlaceProps, SelectOption, Set, SongCategoryProps, SongProps } from "../types";
import { ExtrasProcessor, MassElemSection, OrdinariumProcessor, PsalmLyrics, SongLyrics } from "../components/MassElements";
import { SheetMusicRender } from "../components/SheetMusicRender";
import axios from "axios";
import moment from "moment";
import { SongRender } from "../components/SongRender";

export const MModContext = createContext({} as MModProps);
export const ShowLyricsContext = createContext(true);

export function MassSet(){
    const set_id = +window.location.href.replace(/.*\/(\d+).*/, "$1");
    const place_slug_match = window.location.href.match(/.*\?place=(.*)/);
    const place_slug = place_slug_match ? place_slug_match[1] : null;

    const [set, setSet] = useState({} as Set);
    const [ordinarium, setOrdinarium] = useState([] as OrdinariumProps[]);
    const [ordinarius_colors, setOrdColors] = useState([] as OrdinariumColorProps[]);
    const [formula, setFormula] = useState({} as Formula);
    const [songs, setSongs] = useState([] as SongProps[]);
    const [categories, setCategories] = useState([] as SongCategoryProps[]);
    const [preferences, setPreferences] = useState(["Wejście", "Dary", "Komunia", "Uwielbienie", "Zakończenie"]);
    const [adderFilters, setAdderFilters] = useState({categories: [1], preferences: [0,1,2,3,4]} as AdderFilterProps);
    const [currentPlaceExtras, setCurrentPlaceExtras] = useState([] as Extra[]);
    const [places, setPlaces] = useState([] as PlaceProps[])
    const [showLyrics, setShowLyrics] = useState(true)

    const [addCollector, setAddCollector] = useState({song: undefined, before: undefined} as AddCollectorProps);

    const czsts = ["sIntro", "sOffer", "sCommunion", "sAdoration", "sDismissal"];

    useEffect(() => {
        axios.get("/api/set-data", {params: {
                set_id: set_id,
                place_slug: place_slug,
        }}).then(res => {
            setSet({...res.data.set, thisMassOrder: []});
            setOrdinarium(res.data.ordinarium);
            setOrdColors(res.data.ordinarius_colors);
            setCurrentPlaceExtras(res.data.place_extras);
            setFormula(res.data.formula);
            setSongs(res.data.songs);
            setCategories(res.data.categories);
            setPlaces(res.data.places);
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
    thisMassOrder.filter(el => czsts.includes(el.code)).forEach(el => {
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
    const insertExtras = (extra: Extra, massOrder: MassElem[], prebuild = false) => {
        // insert songs after other songs and before other parts
        const after_flag = (extra.before?.charAt(0) === "s" && extra.before !== "summary" && !prebuild) ?? false;

        const pre = extra.before === "summary"
            ? massOrder[0]
            : massOrder.filter(el2 => el2.code === extra.before)[0];
        let code = (["x", "!"].includes(extra.name.charAt(0)))
            ? extra.name
            : after_flag
                ? extra.before ?? "END"
                : "sB4"+(extra.before ?? "END");
        const same_code_count = thisMassOrder.filter(el => el.code.match(code)).length;
        if(same_code_count > 0 && extra.name.charAt(0) !== "!"){
            code += same_code_count
        }
        const content = (extra.name.charAt(0) === "x") ? undefined : extra.name;

        const addition = {
            code: code,
            label:
                extra.label ? extra.label
                : after_flag ? pre?.label
                : (extra.before && extra.before !== "END")
                    ? `${extra.replace ? "Zastępując" : "Zanim nastąpi"} ${pre?.label}`
                : "Dodatkowo",
            content: content
        };

        if(pre){
            // sole "x" serves as empty, mark "replace" to force-delete
            if(extra.name === "x") thisMassOrder.splice(
                thisMassOrder.indexOf(pre) + (+after_flag),
                (extra.replace) ? 1 : 0
            );
            else thisMassOrder.splice(
                thisMassOrder.indexOf(pre) + (+after_flag),
                (extra.replace) ? 1 : 0,
                addition
            );
        }
        else{
            thisMassOrder.push(addition);
        };
    }

    formula.extras?.forEach((el) => {
        if (set.extras?.filter(sex => (
            sex.name == el.name
            && sex.before == el.before
            && sex.replace == el.replace
        )).length) return;
        insertExtras(el, thisMassOrder, true);
    });
    set.extras?.forEach((el) => {
        if (formula.extras?.filter(fex => (
            fex.name == el.name
            && fex.before == el.before
            && fex.replace == el.replace
        )).length) return;
        insertExtras(el, thisMassOrder, true);
    });
    currentPlaceExtras?.forEach((el) => {
        insertExtras(el, thisMassOrder, true);
    })

    if(set.thisMassOrder.length === 0) setSet({...set, thisMassOrder: thisMassOrder});;

    const Mass = set.thisMassOrder?.map<React.ReactNode>((el, i) => {
        switch(el.code.charAt(0)){
            case "s": // song
                const song = songs.filter(s => s.title === el.content)[0];
                return(
                    <MassElemSection id={el.code} key={i}>
                        <div className="songMeta">
                            <h2>{el.label}</h2>
                            <h1>{el.content}</h1>
                        </div>
                        <SongRender song={song} />
                    </MassElemSection>
                )
            case "p": // psalm
                const part = thisMassOrdinarium.filter(el2 => el2.part === el.label.toLocaleLowerCase())[0];
                const formulaPart = ordinarium
                    .filter(el2 => el2.color_code === baseFormula(formula.name))
                    .find(el2 => el2.part === el.label.toLocaleLowerCase());
                const isNotWielkiPostAklamacja = !(baseFormula(set.formula) === "Wielki Post" && el.code === "pAccl");
                return(
                    <MassElemSection id={el.code} key={i}>
                        <h1>{el.label}</h1>
                        {isNotWielkiPostAklamacja && <SheetMusicRender notes={part.sheet_music_variants} />}
                        {formulaPart && <SheetMusicRender notes={formulaPart.sheet_music_variants} />}
                        <PsalmLyrics lyrics={el.content!} />
                    </MassElemSection>
                )
            case "!": // ordinarius
                return(
                    <MassElemSection id={el.code} key={i}>
                        <OrdinariumProcessor code={el.code} colorCode={set.color} formula={formula.name} />
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
        // force filters
        if(id?.charAt(0) === "s"){
            czsts.forEach((label, i) => {
                if(id.match(label)) setAdderFilters({...adderFilters, preferences: [i]})
            });
        }

        if(useCollector){
            thisMassOrder = set.thisMassOrder!;
            insertExtras(
                {
                    name: addCollector.song,
                    before: addCollector.before,
                    replace: false
                } as Extra,
                thisMassOrder
            );
            setSet({...set, thisMassOrder: thisMassOrder});
        }
        setAddCollector({ before: id } as AddCollectorProps);
        document.getElementById("adder")!.classList.toggle("show");
    }

    function toggleFilters(category: number | null, preference: boolean = false){
        if(category === null){
            if(preference){
                setAdderFilters({ ...adderFilters, preferences: (adderFilters.preferences.length > 0 ? [] : preferences.map((el, i) => i))});
            }else{
                setAdderFilters({ ...adderFilters, categories: (adderFilters.categories.length > 0 ? [] : categories.map(el => el.id)) });
            }
            return;
        }

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

    function placerOn(){
        document.getElementById("placer")!.classList.toggle("show");
    }

    // Mass' summary
    const summary = set.thisMassOrder
        ?.filter(el => !!el.content)
        .filter(el => el.code !== "pAccl")
        .map(el => (el.code === "pPsalm"
            ? {
                ...el,
                content: <ul>
                    {el.content?.split(/\s*%%%\s*/)
                        .map((variant, i) => <li key={i}>
                            <small className="ghost">{variant.match(/^(\/\/(\w)(\r?\n?)+)?(.*)\s/)?.[2]}:</small>&nbsp;
                            {variant.match(/^(\/\/\w(\r?\n?)+)?(.*)\s/)?.[3]}
                        </li>)
                    }
                </ul>
            }
            : {
                ...el, content: <span>{el.content}</span>
            }
        ))

    // Set display color
    const current_color = ordinarius_colors.find(el => el.name === set.color);

    return(<>
        <div className="flex-right center wrap settings">
            <Select name="color" label="Kolor cz.st." options={ordColorOptions} value={set.color} onChange={handleColorChange} style={{ backgroundColor: current_color?.display_color ?? current_color?.name ?? 'none' }}/>
            <Button onClick={() => jumperOn()}>»</Button>
            <Button onClick={() => addModeOn("END")}>+</Button>
            <Button className={[showLyrics && "accent-border"].filter(Boolean).join(" ")}
                onClick={() => setShowLyrics(!showLyrics)}>
                Teksty
            </Button>
            <Button onClick={() => placerOn()}>{currentPlaceExtras ? currentPlaceExtras[0].place : "Miejsce"}</Button>
        </div>

        <div id="jumper" className="modal">
            <h1>Przejdź do</h1>

            <div>
                <h2>Pieśni</h2>
                <div className="flex-right center wrap">
                {set.thisMassOrder
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
                <h2>Części mszy</h2>
                <div className="flex-right center wrap">
                {set.thisMassOrder
                    .filter(el => el.code.charAt(0) !== "s")
                    .map((el, i) =>
                    <Button key={i} className="less-interesting"
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
                <Button className="slick" onClick={() => jumperOn()}>Anuluj</Button>
            </div>
        </div>

        <div id="adder" className="modal">
            <h1>
                Dodaj pieśń
                {addCollector.before !== "END"
                    ? addCollector.before?.charAt(0) !== "s"
                        ? ` przed: ${set.thisMassOrder.filter(el => el.code === addCollector.before)[0]?.label}`
                        : addCollector.before === "summary"
                            ? " na początek zestawu"
                            : ` na ${set.thisMassOrder.filter(el => el.code === addCollector.before)[0]?.label}`
                    : " na koniec zestawu"}
            </h1>

            <h2>Filtry</h2>
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
                    <Button onClick={() => toggleFilters(null)} className="slick">{adderFilters.categories.length > 0 ? "×" : "⁂"}</Button>
                </div>
                <div className="flex-right center wrap">
                {preferences.map((el, i, ar) =>
                    <Button key={i}
                        onClick={() => toggleFilters(ar.indexOf(el), true)}
                        className={adderFilters.preferences.includes(ar.indexOf(el)) ? "accent-border" : ""}
                        >
                        {el}
                    </Button>
                )}
                    <Button onClick={() => toggleFilters(null, true)} className="slick">{adderFilters.preferences.length > 0 ? "×" : "⁂"}</Button>
                </div>
            </div>

            <div className="scroll-list">
                <h2>Specjalne</h2>
                <div className="flex-right center">
                    {[
                        { code: "xExposition", label: "Wystawienie Najświętszego Sakramentu" },
                        { code: "xLorette", label: "Litania Loretańska" },
                        { code: "xHeart", label: "Litania do Serca Jezusowego" },
                        { code: "Życzymy, życzymy", label: "Życzymy, Życzymy" },
                    ].map(({code, label}, i) =>
                        <Button key={i}
                            onClick={() => handleAddCollector("song", code)}
                            className={[
                                addCollector.song === code && "accent-border",
                                "light-button",
                            ].filter(Boolean).join(" ")}
                            >
                            {label}
                        </Button>
                    )}
                </div>

                <h2>Pieśni</h2>
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
            </div>

            <div className="flex-right stretch">
                <Button className="slick" onClick={() => addModeOn()}>Anuluj</Button>
                {addCollector.song && addCollector.before && <Button onClick={() => addModeOn(undefined, true)}>Dodaj</Button>}
            </div>
        </div>

        <div id="placer" className="modal">
            <h1>Zmień miejsce</h1>
            <h2>Wymaga przeładowania, utracisz wprowadzone zmiany!</h2>

            <div className="flex-right center wrap">
                <a href="?">
                    <Button>domyślne</Button>
                </a>
            {places.map((place, i) =>
                <a href={`?place=${slugAndDePL(place.name)}`} key={i}>
                    <Button>{place.name}</Button>
                </a>
            )}
            </div>

            <div className="flex-right stretch">
                <Button className="slick" onClick={() => placerOn()}>Anuluj</Button>
            </div>
        </div>

        <div className="flex-down">
            <MModContext.Provider value={MMod}>
            <ShowLyricsContext.Provider value={showLyrics}>
                <MassElemSection id="summary" uneresable>
                    <h1>Skrót</h1>
                    <div className="grid-2">
                        <div>
                            <h2>Meta</h2>
                            <div className="flex-down center">
                                <DummyInput label="Formuła" value={set.formula} />
                                <DummyInput label="Utworzony" value={moment(set.created_at).format("DD.MM.YYYY")} />
                                <DummyInput label="Zmodyfikowany" value={moment(set.updated_at).format("DD.MM.YYYY")} />
                            </div>
                        </div>
                        <div>
                            <h2>Pieśni i psalm</h2>
                            <ol className="summary">
                            {summary?.map((el, i) =>
                                <li key={i}>
                                    {el.content}
                                    <span className="ghost">{el.label}</span>
                                </li>
                            )}
                            </ol>
                        </div>
                    </div>
                </MassElemSection>
                {Mass}
            </ShowLyricsContext.Provider>
            </MModContext.Provider>
        </div>
    </>)
}
