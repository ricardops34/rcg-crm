import { Component, OnInit, inject, ViewChild } from "@angular/core";
import { CommonModule, Location } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { Router } from "@angular/router";
import {
  PoModule,
  PoNotificationService,
  PoPageAction,
  PoModalComponent,
  PoPageSlideComponent
} from "@po-ui/ng-components";
import { AuthService } from "../../../services/auth";
import { AuthUser } from "../../../services/models/auth.model";

@Component({
  selector: "app-profile",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./profile.html"
})
export class ProfileComponent implements OnInit {
  @ViewChild('avatarModal', { static: true }) avatarModal!: PoModalComponent;
  @ViewChild('pageSlide', { static: true }) pageSlide!: PoPageSlideComponent;

  private authService = inject(AuthService);
  private poNotification = inject(PoNotificationService);
  private router = inject(Router);
  private location = inject(Location);

  user: Partial<AuthUser> = {};
  isLoading: boolean = true;
  passwordData: { current: string; new: string; confirm: string } = {
    current: "",
    new: "",
    confirm: ""
  };

  availableAvatars = Array.from({ length: 14 }, (_, i) => `assets/avatar/avatar_${i.toString().padStart(2, '0')}.png`);

  readonly actions: Array<PoPageAction> = [
    { label: "Salvar Alterações", action: this.saveProfile.bind(this), icon: "po-icon-ok" }
  ];

  ngOnInit() {
    this.pageSlide.open();
    this.loadProfile();
  }

  loadProfile() {
    this.isLoading = true;
    const userData = this.authService.getUser();
    if (userData) {
      this.user = { ...userData };
    }
    this.isLoading = false;
  }

  saveProfile() {
    if (this.passwordData.new && this.passwordData.new !== this.passwordData.confirm) {
      this.poNotification.error("As senhas não conferem.");
      return;
    }

    this.isLoading = true;
    const updateData: Partial<AuthUser> & { password?: string } = {
      name: this.user.name,
      email: this.user.email,
      avatar: this.user.avatar
    };

    if (this.passwordData.new) {
      updateData.password = this.passwordData.new;
    }

    this.authService.updateProfile(updateData).subscribe({
      next: (updatedUser) => {
        this.poNotification.success("Perfil atualizado com sucesso!");
        this.user = { ...updatedUser };
        this.passwordData = { current: "", new: "", confirm: "" };
        this.isLoading = false;
      },
      error: () => {
        this.poNotification.error("Erro ao atualizar perfil.");
        this.isLoading = false;
      }
    });
  }

  openAvatarSelection() {
    this.avatarModal.open();
  }

  selectAvatar(avatarPath: string) {
    this.user.avatar = avatarPath;
    this.avatarModal.close();
  }

  closeProfile() {
    this.location.back();
  }
}
