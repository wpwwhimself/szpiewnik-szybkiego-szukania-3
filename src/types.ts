export interface SongProps{
  title: string,
  categoryCode: number,
  categoryDesc: string | null,
  numberPreis: number | string | null,
  key?: string,
  preferences: string,
  lyrics: string | null,
  sheetMusic: string | null,
};

export interface OrdinariumProps{
  colorCode: string,
  part: string,
  sheetMusic: string,
}

export type InputProps = {
  type?: string,
  name: string,
  label: string,
  value?: string | number | null,
} & ({
  type?: "text";
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
} | {
  type?: "TEXT";
  onChange?: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
})

export type SelectProps = {
  name: string,
  label: string,
  value?: string | number,
  firstEmpty?: boolean,
  options?: SelectOption[],
  onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void;
}

export interface SelectOption{
  key: number | string,
  label: string,
}