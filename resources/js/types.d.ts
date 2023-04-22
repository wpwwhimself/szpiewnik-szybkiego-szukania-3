import { ReactNode } from "react";

export interface AddCollectorProps {
    song?: string,
    before?: string,
}
export interface HandleAddCollectorProps{
    (updatingField: 'song' | 'before', value?: string): void;
}

export interface MModProps{
    prepareMassElemErase: (id: string) => void,
    eraseMassElem: (id: string) => void,
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
}

export interface OrdinariumProps{
    id: number,
    color_code: string,
    part: string,
    sheet_music: string,
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
    gloria_present: boolean,
    extra?: Extra[],
}

export interface Set{
    name: string,
    formula: string,
    color: string,
    sIntro: string,
    pPsalm: string,
    pAccl: string,
    sOffer: string,
    sCommunion: string,
    sAdoration: string,
    sDismissal: string,
    extra?: Extra[],
    thisMassOrder?: MassElem[],
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

export type SelectProps = {
    name: string,
    label: string,
    value?: string | number,
    firstEmpty?: boolean,
    options?: SelectOption[],
    onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void;
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
