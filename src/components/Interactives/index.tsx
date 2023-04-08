import "./style.css";
import { InputProps, PreferencesProps, SelectProps } from "../../types";
import { ButtonHTMLAttributes } from "react";

export function Input({type, name, label, value, onChange}: InputProps){
  switch(type){
    case "TEXT": return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <textarea name={name} id={name} defaultValue={value ?? undefined} onChange={onChange} />
      </div>
    );
    case "checkbox": return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <input type={type} name={name} id={name} defaultChecked={value} onChange={onChange} />
      </div>
    );
    case "text": return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <input type={type} name={name} id={name} defaultValue={value ?? undefined} onChange={onChange} />
      </div>
    );
    default: return(
      <div className="input-container">
        <label htmlFor={name}>{label}</label>
        <input type={type} name={name} id={name} defaultValue={value ?? undefined} />
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
        {options?.map(el => <option key={el.value} value={el.value}>{el.label}</option>)}
      </select>
    </div>
  )
}

export function Preferences({preferences, onChange}: PreferencesProps){
  const place_prefs: boolean[] = [];
  preferences.split("/").forEach(el => {
    switch(el){
      case "0": place_prefs.push(false); break;
      case "1": place_prefs.push(true); break;
    }
  });
  let other_prefs: string = preferences.split("/")[5];
  if(other_prefs === "0") other_prefs = "";

  const possibilities = [
    { label: "Wstęp", name: "pref0" },
    { label: "Dary", name: "pref1" },
    { label: "Komunia", name: "pref2" },
    { label: "Uwielb.", name: "pref3" },
    { label: "Zakończ.", name: "pref4" },
  ];

  return(
    <div className="input-container">
      <Input type="text" name="pref5" label="Uwagi" value={other_prefs} onChange={onChange} />
      <div className="flex-right center">
      {possibilities.map((labels, ind) =>
        <Input key={labels.name}
          type="checkbox"
          name={labels.name}
          label={labels.label}
          value={place_prefs[ind]}
          onChange={onChange}
        />)}
      </div>
    </div>
  )
}

export function Button(props: ButtonHTMLAttributes<HTMLButtonElement>){
  return(
    <button {...props}>{props.children}</button>
  )
}
