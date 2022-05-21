import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PublicationService {

  private urlPublicationControllerSelectCommunityPublications = ADDRESS_SERVER + "PublicationController/selectCommunityPublications.php";
  private urlPublicationControllerSelect = ADDRESS_SERVER + "PublicationController/select.php";
  private urlPublicationControllerInsert = ADDRESS_SERVER + "PublicationController/insert.php";
  private urlPublicationControllerCheckLike = ADDRESS_SERVER + "PublicationController/checkLike.php";
  private urlPublicationControllerAddLike = ADDRESS_SERVER + "PublicationController/addLike.php";
  private urlPublicationControllerRemoveLike = ADDRESS_SERVER + "PublicationController/removeLike.php";


  constructor(private httpClient: HttpClient) { }

  /**
   * Select a range of community publications
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @param numPage page to select (range of publications)
   * @returns Observable with data
   */
  selectPublications(sessionToken: string, idCommunity: number, numPage: number = 0) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    formData.append("num_page",numPage.toString());
    return this.httpClient.post(this.urlPublicationControllerSelectCommunityPublications, formData);
  }

  /**
   * Select a specific publication
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @param idPublication publication id
   * @returns Observable with data
   */
  selectPublication(sessionToken: string, idCommunity: number, idPublication: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community",idCommunity.toString());
    formData.append("id",idPublication.toString());
    return this.httpClient.post(this.urlPublicationControllerSelect, formData);
  }

  /**
   * Insert a new publication
   *
   * @param sessionToken user session token
   * @param idCommunity community id
   * @param textPublication text of the publication
   * @param commentTo id of the commented publication (if comment)
   * @returns Observable with data
   */
  insertPublication(sessionToken: string, idCommunity: number, textPublication: string, commentTo: number = 0) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community",idCommunity.toString());
    formData.append("text_publication",textPublication);
    formData.append("comment_to",commentTo.toString());
    return this.httpClient.post(this.urlPublicationControllerInsert, formData);
  }

  /**
   * Check if the user has already liked the publication
   *
   * @param sessionToken user session token
   * @param idPublication publication id
   * @returns Observable with data
   */
  checkLike(sessionToken: string, idPublication: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idPublication.toString());
    return this.httpClient.post(this.urlPublicationControllerCheckLike, formData);
  }

  /**
   * Add a like to the publication
   *
   * @param sessionToken user session token
   * @param idPublication publication id
   * @returns Observable with data
   */
  addLike(sessionToken: string, idPublication: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idPublication.toString());
    return this.httpClient.post(this.urlPublicationControllerAddLike, formData);
  }

  /**
   * Remove a like to the publication
   *
   * @param sessionToken user session token
   * @param idPublication publication id
   * @returns Observable with data
   */
  removeLike(sessionToken: string, idPublication: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idPublication.toString());
    return this.httpClient.post(this.urlPublicationControllerRemoveLike, formData);
  }
}
