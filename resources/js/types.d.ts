import { CSSProperties, ReactNode } from "react";

export interface AddCollectorProps {
    song?: string,
    before?: string,
}

export interface AdderFilterProps{
    categories: number[],
    preferences: number[],
}

export interface HandleAddCollectorProps{
    (updatingField: 'song' | 'before', value?: string): void;
}

export interface MModProps{
    addMassElem: (id?: string) => void,
    eraseMassElem: (id: string) => void,
}

export interface PlaceProps{
    name: string,
}

export interface SongProps{
    title: string,
    song_category_id: number,
    category_desc: string | null,
    number_preis: number | string | null,
    key: string | null,
    preferences: string,
    lyrics: string | null,
    sheet_music: string | null,
    sheet_music_variants: string[],
    lyrics_variants: string[],
}

export interface SongCategoryProps{
    id: number,
    name: string,
    created_at: string | null,
    updated_at: string | null,
}

export interface OrdinariumProps{
    id: number,
    color_code: string,
    part: string,
    sheet_music: string,
    sheet_music_variants: string[],
}

export interface OrdinariumColorProps{
    name: string,
    display_color: string | null,
    display_name: string,
    desc: string,
}

export interface Extra{
    id: number,
    formula?: string,
    place?: string,
    name: string,
    before: string | null,
    replace: boolean,
}

export interface Formula{
    name: string,
    extras?: Extra[],
}

export interface Set{
    name: string,
    formula: string,
    color: string,
    sIntro: string | null,
    pPsalm: string | null,
    pAccl: string | null,
    sOffer: string | null,
    sCommunion: string | null,
    sAdoration: string | null,
    sDismissal: string | null,
    created_at: string | null,
    updated_at: string | null,
    extras?: Extra[],
    thisMassOrder: MassElem[],
}

export interface MassElem{
    code: string,
    label: string,
    content?: string,
}

export interface MassElemSectionProps{
    id: string,
    uneresable?: boolean,
    children: ReactNode,
}

export interface OrdinariumProcessorProps{
    code: string,
    colorCode: string,
}

export type InputProps = {
    name: string,
    label: string,
    disabled?: boolean,
} & ({
    type?: "TEXT";
    value?: string | number | null,
    onChange?: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
} | {
    type: "checkbox",
    value?: boolean,
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
} | {
    type?: "text" | "email" | "password",
    value?: string | number | null,
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
})

export interface DummyInputProps{
    label: string,
    value: string | number | null,
}

export type SelectProps = {
    name: string,
    label: string,
    value?: string | number,
    firstEmpty?: boolean,
    options?: SelectOption[],
    onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void;
    style?: CSSProperties,
}

export interface PreferencesProps{
    preferences: string,
    onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

export interface SelectOption{
    value: number | string,
    label: string,
    key?: number,
}
