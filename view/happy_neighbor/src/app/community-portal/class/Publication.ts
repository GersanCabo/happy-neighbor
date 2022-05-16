export interface Publication {
  id: number,
  textPublication: string,
  likes: number,
  datePublication: string,
  idUser: number,
  commentTo: Publication|null
}
