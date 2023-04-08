import { Section } from "../../components/Section"
import "./style.css"
import { ordinarium, ordinarium_colors } from "../../data"
import { Link } from "react-router-dom"
import { useLocation, useNavigate } from "react-router-dom"
import { useState } from "react";
import axios from "axios";
import { Button, Input } from "../../components/Interactives";
import { Notation } from "react-abc";

export function Ordinarium(){
  return(
    <Section title="Części stałe">
      <div className="grid-3">
      {ordinarium_colors.map(color => <div className="ord-tile" key={color.name}>
        <div className="ord-title-box" style={{ borderColor: color.displayColor ?? color.name }}>
          <h1>{color.displayName}</h1>
          <p>{color.desc}</p>
        </div>
        <div className="flex-right wrap center">
        {ordinarium
          .filter(part => part.colorCode === color.name)
          .map((part, ind) => 
          <Link to={`${part.colorCode}-${part.part}`} key={ind}>
            {part.part}
          </Link>
        )}
        </div>
      </div>)}
      </div>
    </Section>
  )
}

export function OrdinariumEdit(){
  const navigate = useNavigate();
  const title_match: string[] = useLocation().pathname.replace(/\/ordinarium\/(.*)/, "$1").split("-");

  const original_ordinarius = ordinarium.filter(sought => sought.colorCode === title_match[0] && sought.part === title_match[1])[0];
  const ordinarius_id = ordinarium.indexOf(original_ordinarius);

  const [ordinarius, setOrdinarius] = useState(ordinarium.filter(sought => sought.colorCode === title_match[0] && sought.part === title_match[1])[0]);

  const handleChange = (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = event.target;
    setOrdinarius({ ...ordinarius, [name]: value });
  };

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    axios.post("http://localhost:5000/api/save-ordinarius", { itemToChange: ordinarius, item_id: ordinarius_id }).then((response) => {
      console.log(response);
    });
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
        <div id="sheet-container" className="flex-right center">
          <Notation notation={ordinarius.sheetMusic ?? ""} />
        </div>
        <Button type="submit">Zatwierdź i wróć</Button>
      </form>
    </Section>
  )
}