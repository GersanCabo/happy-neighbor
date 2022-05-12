import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from 'src/app/global/services/user.service';
import { ValidateTokenService } from 'src/app/global/services/validate-token.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  sessionToken:string|null = null;

  constructor(private userService: UserService, private validateTokenService: ValidateTokenService, private router: Router) {
    this.sessionToken = sessionStorage.getItem('session_token');
  }

  ngOnInit(): void {
    if (this.sessionToken != null) {
      this.validateToken(this.sessionToken);
    } else {
      this.router.navigate(['/login']);
    }
  }

 /**
  * Check if the session token is validate
  *
  * @param sessionToken user session token
  */
  validateToken(sessionToken: string) {
    this.validateTokenService.validateToken(sessionToken);
  }

}
