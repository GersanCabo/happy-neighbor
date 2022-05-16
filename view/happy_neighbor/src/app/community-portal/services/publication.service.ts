import { Injectable } from '@angular/core';
import { ADDRESS_SERVER } from 'src/app/global/constants/address-server';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PublicationService {

  private urlPublicationControllerSelectCommunityPublications = ADDRESS_SERVER + "PublicationController/selectCommunityPublications.php";
  private urlPublicationControllerSelect = ADDRESS_SERVER + "PublicationController/select.php";

  constructor(private httpClient: HttpClient) { }

  selectPublications(sessionToken: string, idCommunity: number, numPage: number = 0) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id",idCommunity.toString());
    formData.append("num_page",numPage.toString());
    return this.httpClient.post(this.urlPublicationControllerSelectCommunityPublications, formData);
  }

  selectPublication(sessionToken: string, idCommunity: number, idPublication: number) {
    let formData: FormData = new FormData();
    formData.append("session_token",sessionToken);
    formData.append("id_community",idCommunity.toString());
    formData.append("id",idPublication.toString());
    return this.httpClient.post(this.urlPublicationControllerSelect, formData);
  }
}
