import { Section } from "../../components/Section";
import "./style.css";
import { formulas } from "../../data";
import { Link } from "react-router-dom";
import { slugAndDePL, massOrder } from "../../helpers";
import { useState } from "react";
import { Button, Input, Select } from "../../components/Interactives";
import { Extra } from "../../types";
import { useLocation, useNavigate } from "react-router-dom";
import axios from "axios";

export function Formulas(){
  return(
    <Section title="Lista formuł">
      <div className="flex-right wrap">
      {formulas.map((formula, i) => 
        <Link to={`${slugAndDePL(formula.name)}`} key={i}>
          {formula.name}
        </Link>
      )}
      </div>
    </Section>
  )
}

export function FormulasEdit(){
  const navigate = useNavigate();
  const title_match: string = useLocation().pathname.replace(/\/formulas\/(.*)/, "$1");

  const original_formula = formulas.filter(sought => slugAndDePL(sought.name) === title_match)[0];
  const formula_id = formulas.indexOf(original_formula);

  const [formula, setFormula] = useState(formulas.filter(sought => slugAndDePL(sought.name) === title_match)[0]);

  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value, checked } = event.target;
    setFormula({ ...formula, [name]: (name === "gloriaPresent") ? checked : value });
  };

  const handleExtraChange = (event: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = event.target;
    const change_id = +event.target.closest(".formula-change")!.getAttribute("data-id")!;
    let extra = formula.extra!;
    extra[change_id][name.replace(/(.*)\[\d\]/, "$1") as keyof Extra] = value;
    setFormula({ ...formula, extra: extra });
    console.log(formula.extra![change_id]);
  }

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    axios.post("http://localhost:5000/api/save-formula", { itemToChange: formula, item_id: formula_id }).then((response) => {
      console.log(response);
    });
    navigate("/formulas");
  };

  return(
    <Section title={`${formula.name} | Edycja formuły`}>
      <h1>Parametry formuły</h1>
      <form onSubmit={handleSubmit}>
        <div className="flex-right center">
          <Input type="text" name="name" label="Nazwa" value={formula.name} onChange={handleChange} />
          <Input type="checkbox" name="gloriaPresent" label="Gloria" value={formula.gloriaPresent} onChange={handleChange} />
        </div>
        <h2>Zmiany</h2>
        <div className="flex-right center wrap">
        {formula.extra?.map((extra, i) => 
          <div key={i} className="formula-change" data-id={i}>
            <Input type="text" name={`songName[${i}]`} label="Pieśń" value={extra.songName} onChange={handleExtraChange} />
            <Select name={`preWhere[${i}]`} label="Przed" options={massOrder} firstEmpty value={extra.preWhere} onChange={handleExtraChange} />
          </div>
        )}
        </div>
        <Button type="submit">Zatwierdź i wróć</Button>
      </form>
    </Section>
  )
}