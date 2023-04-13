import React from "react";
import { useLocation } from "react-router-dom";
import { Section } from "../../components/Section"
import style from "./style.module.css"
import { createContext, useState } from "react";
import { sets, ordinarium_colors, formulas, songs, ordinarium } from "../../data";
import { slugAndDePL, massOrder, baseFormula } from "../../helpers";
import { Button, Input, Select } from "../../components/Interactives";
import { MModProps, MassElem, SelectOption, Set } from "../../types";
import { ExtrasProcessor, MassElemSection, OrdinariumProcessor, PsalmLyrics, SongLyrics } from "../../components/MassElements";
import { Notation } from "react-abc";

export const MModContext = createContext({} as MModProps);

export function MassSet(){
  const title_match: string = useLocation().pathname.replace(/\/sets\/show\/(.*)/, "$1");
  const [set, setSet] = useState(sets.filter(sought => slugAndDePL(sought.name) === title_match)[0]);
  const formula = formulas.filter(sought => sought.name === baseFormula(set.formulaName))[0];

  const ordColorOptions: SelectOption[] = [];
  ordinarium_colors.forEach(color => ordColorOptions.push({ value: color.name, label: color.displayName }));
  const handleColorChange = (ev: React.ChangeEvent<HTMLSelectElement>) => {
    setSet({ ...set, color: ev.target.value });
  }
  const thisMassOrdinarium = ordinarium.filter(el => el.colorCode === set.color);

  /**
   * This mass' order
   */
  let thisMassOrder: MassElem[] = [];
  massOrder.map(el => thisMassOrder.push({
    code: el.value as string,
    label: el.label,
    content: set[el.value as keyof Set] as string,
  }));

  //splitting comunnion songs
  const com = thisMassOrder.filter(el => el.code === "sCommunion")[0];
  com.content!.split("$").forEach((title, i) => {
    thisMassOrder.splice(
      thisMassOrder.indexOf(com),
      0,
      { code: `${com.code}${i}`, label: com.label, content: title }
    );
  });
  thisMassOrder.splice(thisMassOrder.indexOf(com), 1);

  //formula modifications
  if(!formula.gloriaPresent) thisMassOrder = thisMassOrder.filter(el => el.code !== "oGloria");
  formula.extra?.forEach((el) => {
    const pre = thisMassOrder.filter(el2 => el2.code === el.preWhere)[0];
    const addition = (el.songName.charAt(0) === "x") ?
      { code: `${el.songName}`, label: `Zanim nastąpi ${pre.label}`, content: undefined } :
      { code: `sB4${el.preWhere}`, label: `Zanim nastąpi ${pre.label}`, content: el.songName };
    if(pre) thisMassOrder.splice(
      thisMassOrder.indexOf(pre),
      (el.replace) ? 1 : 0,
      addition
    );
    else thisMassOrder.push({ code: `sOutro`, label: `Dodatkowo`, content: el.songName });
  });

  if(set.thisMassOrder === undefined) setSet({...set, thisMassOrder: thisMassOrder});

  const Mass = set.thisMassOrder?.map<React.ReactNode>((el, i) => {
    switch(el.code.charAt(0)){
      case "s": // song
        const song = songs.filter(s => s.title === el.content)[0];
        return(
          <MassElemSection id={el.code} key={i}>
            <div className={style.songMeta}>
              <h2>{el.label}</h2>
              <h1>{song.title}</h1>
              <div className="flex-right center">
                <Input type="text" name="" label="Tonacja" value={song.key} disabled />
                <Input type="text" name="" label="Kategoria" value={song.categoryDesc} disabled />
                <Input type="text" name="" label="Numer w śpiewniku Preis" value={song.numberPreis} disabled />
              </div>
              {song.sheetMusic && <div className="flex-right center sheet-container">
                <Notation notation={song.sheetMusic} />
              </div>}
              <SongLyrics title={song.title} />
            </div>
          </MassElemSection>
        )
      case "p": // psalm
        const part = thisMassOrdinarium.filter(el2 => el2.part === el.label.toLocaleLowerCase())[0];
        const formulaPart = ordinarium
          .filter(el2 => el2.colorCode === baseFormula(formula.name))
          .filter(el2 => el2.part === el.label.toLocaleLowerCase())[0];
        const isNotWielkiPostAklamacja = !(baseFormula(set.formulaName) === "Wielki Post" && el.code === "pAccl");
        return(
          <MassElemSection id={el.code} key={i}>
            <h1>{el.label}</h1>
            <div className="flex-down center sheet-container">
              {isNotWielkiPostAklamacja && <Notation notation={part.sheetMusic} />}
              {formulaPart && <Notation notation={formulaPart.sheetMusic} />}
            </div>
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
    prepareMassElemErase: (id) => {
      const hide = id.charAt(0) === "!";
      if(hide) id = id.substring(1);
      document.querySelector<HTMLElement>(`#${id} .massElemEraser button:nth-child(1)`)!.style.display = (hide) ? "none" : "block";
    },
    eraseMassElem: (id) => {
      thisMassOrder = set.thisMassOrder!.filter(el => el.code !== id);
      setSet({...set, thisMassOrder: thisMassOrder});
    }
  }

  /**
   * Mass' summary
   */
  const summary = set.thisMassOrder
    ?.filter(el => el.content !== undefined)
    .filter(el => el.code !== "pAccl");

  return(
    <Section title={`${set.name}`}>
      <div className={`flex-right center ${style.settings}`}>
        <Select name="color" label="Kolor cz.st." options={ordColorOptions} value={set.color} onChange={handleColorChange}/>
        {summary?.map((el, i) =>
          <Button
            onClick={() => document.getElementById(el.code)?.scrollIntoView({behavior: "smooth", block: "center"})}
            >
            {el.label.substring(0, 3)}
          </Button>
        )}
      </div>

      <div className="flex-down">
        <MModContext.Provider value={MMod}>
          <MassElemSection id="summary" uneresable>
            <h1>Skrót</h1>
            <div className="grid-2">
              <div>
                <h2>Meta</h2>
                <div className="flex-right wrap center">
                  <Input type="text" name="" label="Formuła" disabled value={set.formulaName} />
                  <Input type="text" name="" label="Utworzony" disabled value={set.createdAt} />
                </div>
              </div>
              <div>
                <h2>Pieśni i psalm</h2>
                <ol className={style.summary}>
                {summary?.map((el, i) =>
                  <li key={i}>
                    <b>{
                      (el.content!.indexOf("\n") > -1) ?
                      el.content!.substring(0, el.content!.indexOf("\n")) :
                      el.content
                    }</b>
                    <span>{el.label}</span>
                  </li>
                )}
                </ol>
              </div>
            </div>
          </MassElemSection>
          {Mass}
        </MModContext.Provider>
      </div>

    </Section>
  )
}
