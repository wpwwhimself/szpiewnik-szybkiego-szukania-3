import "./style.css";
import { InputProps, PreferencesProps, SelectProps } from "../../types";

export function Input({type, name, label, value}: InputProps){
  switch(type){
    case "TEXT": return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <textarea name={name} id={name} defaultValue={value ?? undefined} />
      </div>
    );
    case "checkbox": return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <input type="checkbox" name={name} id={name} defaultChecked={value} />
      </div>
    );
    default: return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <input type={type ?? "text"} name={name} id={name} defaultValue={value ?? undefined} />
      </div>
    );
  }
}

export function Select({name, label, value, firstEmpty, options, onChange}: SelectProps){
  return(
    <div className="input-container">
      <label htmlFor={name}>{label}</label>
      <select name={name} id={name} onChange={onChange} defaultValue={value}>
        {firstEmpty && <option key="_first"></option>}
        {options?.map(el => <option key={el.key} value={el.key}>{el.label}</option>)}
      </select>
    </div>
  )
}

export function Preferences({preferences}: PreferencesProps){
  const place_prefs: boolean[] = [];
  preferences.split("/").forEach(el => {
    switch(el){
      case "0": place_prefs.push(false); break;
      case "1": place_prefs.push(true); break;
    }
  });

  const possibilities = [
    { label: "Wstęp", name: "pref_wst" },
    { label: "Dary", name: "pref_dar" },
    { label: "Komunia", name: "pref_kom" },
    { label: "Uwielb.", name: "pref_uwi" },
    { label: "Zakończ.", name: "pref_zak" },
  ];

  return(
    <div className="flex-right center">
    {possibilities.map((labels, ind) =>
      <Input key={labels.name}
        type="checkbox"
        name={labels.name}
        label={labels.label}
        value={place_prefs[ind]}
      />)}
    </div>
  )
}
