import { Section } from "../../components/Section";
import "./style.css";
import { songs, song_categories } from "../../data";
import { Link } from "react-router-dom";
import { slugAndDePL } from "../../helpers";

export function Songs(){
  return(
    <Section title="Lista pieśni">
      {song_categories.map(category => <>
        <h1 className="cap-initial">{category.kategoria}</h1>
        <div className="flex-right wrap center">
        {songs.filter(song => song.categoryCode === category.id).map(song => 
          <Link to={`/songs/${slugAndDePL(song.title)}`} key={song.title}>
            {song.title}
          </Link>)}
        </div>
      </>)}
    </Section>
  )
}
