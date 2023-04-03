import "./style.css";

interface InputProps{
  type?: string,
  name: string,
  label: string,
  value?: string | number,
  firstEmpty?: boolean,
  options?: SelectOption[],
}

export interface SelectOption{
  key: number | string,
  label: string,
}

export function Input({type, name, label, value}: InputProps){
  if(type === "TEXT") return(
    <div className="input-container">
      <label htmlFor={name}>{label}</label>
      <textarea name={name} id={name}></textarea>
    </div>
  );

  return(
    <div className="input-container">
      <label htmlFor={name}>{label}</label>
      <input type={type ?? "text"} name={name} id={name} value={value} />
    </div>
  )
}

export function Select({name, label, value, firstEmpty, options}: InputProps){
  return(
    <div className="input-container">
      <label htmlFor={name}>{label}</label>
      <select name={name} id={name}>
        {firstEmpty && <option key="_first"></option>}
        {options?.map(el => <option key={el.key}>{el.label}</option>)}
      </select>
    </div>
  )
}
