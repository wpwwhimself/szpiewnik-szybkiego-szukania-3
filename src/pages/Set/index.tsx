import { Link, useLocation } from "react-router-dom";
import { Section } from "../../components/Section"
import style from "./style.module.css"
import { useState } from "react";
import { sets, ordinarium_colors, formulas } from "../../data";
import { slugAndDePL, massOrder } from "../../helpers";
import { Select } from "../../components/Interactives";
import { MassElem, SelectOption, Set } from "../../types";

export function MassSet(){
  const title_match: string = useLocation().pathname.replace(/\/set\/(.*)/, "$1");
  const [set, setSet] = useState(sets.filter(sought => slugAndDePL(sought.name) === title_match)[0]);
  const formula = formulas.filter(sought => sought.name === set.formulaName)[0];

  const ordColorOptions: SelectOption[] = [];
  ordinarium_colors.forEach(color => ordColorOptions.push({ value: color.name, label: color.displayName }));
  const handleColorChange = (ev: React.ChangeEvent<HTMLSelectElement>) => {
    setSet({ ...set, color: ev.target.value });
  }

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
  com.content.split("$").forEach((title, i) => {
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
    if(pre) thisMassOrder.splice(
      thisMassOrder.indexOf(pre),
      0,
      { code: `sB4${el.preWhere}`, label: `Zanim nastąpi ${pre.label}`, content: el.songName }
    );
    else thisMassOrder.push({ code: `sOutro`, label: `Dodatkowo`, content: el.songName });
  });

  const Mass = thisMassOrder.map<React.ReactNode>((el, i) => {
    switch(el.code.charAt(0)){
      case "s": // song
      case "p": // psalm
      case "o": // ordinarius
      default:
        return(
          <div key={i}>
            <h1>{el.label}</h1>
          </div>
        )
    }
  });

  /**
   * Mass' summary
   */
  const summary = thisMassOrder
    .filter(el => el.content !== undefined)
    .filter(el => el.code !== "pAccl")
    .map((el, i) => 
    <a key={i} href={`#${el.code}`} className="button">
      <span>{el.label}</span>
      <h3>{
        (el.content.indexOf("\n") > -1) ?
        el.content.substring(0, el.content.indexOf("\n")) :
        el.content
      }</h3>
    </a>
  );

  return(
    <Section title={`${set.name}`}>
      <div className={`flex-right ${style.settings}`}>
        <h2>Ustawienia</h2>
        <Select name="color" label="Kolor" options={ordColorOptions} value={set.color} onChange={handleColorChange}/>
      </div>

      <h1>Skrót</h1>
      <div className={`flex-right wrap center ${style.summary}`}>
        {summary}
      </div>

      <div>
      {Mass}
      </div>

    </Section>
  )
}