import { Component, OnInit } from '@angular/core';
import { UserCommunitiesService } from '../../services/user-communities.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-list-communities',
  templateUrl: './list-communities.component.html',
  styleUrls: ['./list-communities.component.scss'],
  providers: [UserCommunitiesService]
})
export class ListCommunitiesComponent implements OnInit {

  sessionToken:string|null = null;

  constructor(private router: Router, private userCommunitiesService: UserCommunitiesService) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    if (this.sessionToken == null) {
      this.router.navigate(['/login']);
    }
    this.downUserCommunities();
  }

  downUserCommunities() {
    if (this.sessionToken != null) {
      this.userCommunitiesService.selectUserCommunities(this.sessionToken);
    }
  }

}
