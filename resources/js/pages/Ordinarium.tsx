import { Section } from "../components/Section"
import { ordinarium, ordinarius_colors } from "../data"
import { Link } from "react-router-dom"
import { useLocation, useNavigate } from "react-router-dom"
import React, { useState } from "react";
import { Button, Input } from "../components/Interactives";
import Abcjs from "react-abcjs";
import { dataChange, slugAndDePL } from "../helpers"

export function Ordinarium(){
  return(
    <Section title="Części stałe">
      <div className="grid-3">
      {ordinarius_colors.map((color, i) =>
        <div className="ordTile" key={i}>
          <div className="ordTitleBox" style={{ borderColor: color.displayColor ?? color.name }}>
            <h1>{color.displayName}</h1>
            <p>{color.desc}</p>
          </div>
          <div className="flex-right wrap center">
          {ordinarium
            .filter(part => part.colorCode === color.name)
            .map((part, ind) =>
            <Link to={`${part.colorCode}_${part.part}`} key={ind}>
              {part.part}
            </Link>
          )}
          </div>
        </div>)}
      </div>
      <div className="grid-2">
        <div className="ordTile">
          <div className="ordTitleBox">
            <h1>Uniwersalne</h1>
            <p>dużo ich nie ma, ale...</p>
          </div>
          <div className="flex-right wrap center">
          {ordinarium.filter(el => el.colorCode === "*").map((ord, i) =>
            <Link to={`*_${ord.part}`} key={i}>{ord.part}</Link>
          )}
          </div>
        </div>
        <div className="ordTile">
          <div className="ordTitleBox">
            <h1>Okazjonalne</h1>
            <p>na potrzeby świąt</p>
          </div>
          <div className="flex-right wrap center">
          {ordinarium
            .filter(el => el.colorCode.charAt(0).match(/[A-Z]/))
            .map((el, ind) =>
              <Link to={`${slugAndDePL(el.colorCode)}_${el.part}`} key={ind}>
                {el.part} ({el.colorCode})
              </Link>
          )}
          </div>
        </div>
      </div>
    </Section>
  )
}

export function OrdinariumEdit(){
  const navigate = useNavigate();
  const title_match: string[] = useLocation().pathname.replace(/\/ordinarium\/(.*)/, "$1").split("_");

  const original_ordinarius = ordinarium.filter(sought => slugAndDePL(sought.colorCode) === title_match[0] && sought.part === title_match[1])[0];
  const ordinarius_id = ordinarium.indexOf(original_ordinarius);

  const [ordinarius, setOrdinarius] = useState(ordinarium.filter(sought => slugAndDePL(sought.colorCode) === title_match[0] && sought.part === title_match[1])[0]);

  const handleChange = (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = event.target;
    setOrdinarius({ ...ordinarius, [name]: value });
  };

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    dataChange("ordinarium", ordinarius, ordinarius_id);
    navigate("/ordinarium");
  };

  const capInit = (str: string): string => str.charAt(0).toUpperCase() + str.slice(1);

  return(
    <Section title={`${capInit(ordinarius.part)} | Edycja części stałej`}>
      <h1>Parametry części stałej</h1>
      <form onSubmit={handleSubmit}>
        <div>
          <Input type="TEXT" name="sheetMusic" label="Nuty" value={ordinarius.sheetMusic ?? undefined} onChange={handleChange} />
        </div>
        <div className="flex-right center sheet-container">
          <Abcjs abcNotation={ordinarius.sheetMusic ?? ""} />
        </div>
        <div className="flex-right stretch">
          <Button type="submit">Zatwierdź i wróć</Button>
        </div>
      </form>
    </Section>
  )
}
