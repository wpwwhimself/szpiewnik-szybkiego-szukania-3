import { Section } from "../../components/Section"
import "./style.css"
import { ordinarium, ordinarium_colors } from "../../data"
import { Link } from "react-router-dom"

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
          .sort((a, b) => a.part.charCodeAt(0) - b.part.charCodeAt(0))
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