import { SongProps, OrdinariumProps } from "../types"
import songs_raw from "./songs.json"
import song_categories_raw from "./song_categories.json"
import ordinarium_raw from "./ordinarium.json"

export const songs: SongProps[] = songs_raw;
export const song_categories = song_categories_raw;
export const ordinarium: OrdinariumProps[] = ordinarium_raw;
