import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './register.html',
  styleUrl: './register.css'
})
export class Register {
  name = '';
  email = '';
  password = '';
  password_confirmation = '';
  errorMessages: string[] = [];
  loading = false;

  constructor(private authService: Auth, private router: Router) {}

  onSubmit(): void {
    this.errorMessages = [];
    this.loading = true;

    this.authService.register(this.name, this.email, this.password, this.password_confirmation).subscribe({
      next: () => {
        this.loading = false;
        this.router.navigate(['/mes-tickets']);
      },
      error: (err) => {
        this.loading = false;
        if (err.error?.errors) {
          this.errorMessages = Object.values(err.error.errors).flat() as string[];
        } else {
          this.errorMessages = ['Une erreur est survenue.'];
        }
      }
    });
  }
}