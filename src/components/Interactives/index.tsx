import "./style.css";
import { InputProps, SelectProps } from "../../types";

export function Input({type, name, label, value}: InputProps){
  if(type === "TEXT") return(
    <div className="input-container">
      <label htmlFor={name}>{label}</label>
      <textarea name={name} id={name} defaultValue={value ?? undefined} />
    </div>
  );

  return(
    <div className="input-container">
      <label htmlFor={name}>{label}</label>
      <input type={type ?? "text"} name={name} id={name} defaultValue={value ?? undefined} />
    </div>
  )
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
