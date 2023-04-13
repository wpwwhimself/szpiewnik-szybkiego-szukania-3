import { SongProps, OrdinariumProps, OrdinariumColorProps, Formula, Set } from "../types"
import songs_raw from "./songs.json"
import song_categories_raw from "./song_categories.json"
import ordinarium_raw from "./ordinarium.json"
import ordinarium_colors_raw from "./ordinarium_colors.json"
import formulas_raw from "./formulas.json"
import sets_raw from "./sets.json"

export const songs: SongProps[] = songs_raw;
export const song_categories = song_categories_raw;
export const ordinarium: OrdinariumProps[] = ordinarium_raw;
export const ordinarium_colors: OrdinariumColorProps[] = ordinarium_colors_raw;
export const formulas: Formula[] = formulas_raw;
export const sets: Set[] = sets_raw;
