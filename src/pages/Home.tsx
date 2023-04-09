import { Link } from "react-router-dom";
import { Section } from "../components/Section";
import { sets } from "../data";
import { slugAndDePL } from "../helpers";

export function Home(){
  return(
    <Section title="Pulpit">
      <h1>Załaduj mszę</h1>
      <div className="flex-right center wrap">
      {sets.map((set, i) => 
        <Link key={i} to={`set/${slugAndDePL(set.name)}`}>
          {set.name}
        </Link>
      )}
      </div>
    </Section>
  );
}
