import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { CdkDragDrop, DragDropModule, moveItemInArray } from "@angular/cdk/drag-drop";
import { PoModule } from "@po-ui/ng-components";

type ConfiguratorFieldType = "string" | "number" | "date" | "select" | "boolean" | "textarea";

interface PaletteItem {
  type: ConfiguratorFieldType;
  label: string;
  icon: string;
}

interface BuilderField {
  id: string;
  property: string;
  label: string;
  type: ConfiguratorFieldType;
  required: boolean;
  mask: string;
  gridMd: number;
  placeholder: string;
}

@Component({
  selector: "app-configurator-workbench",
  standalone: true,
  imports: [CommonModule, FormsModule, DragDropModule, PoModule],
  templateUrl: "./configurator-workbench.html",
  styleUrl: "./configurator-workbench.css"
})
export class ConfiguratorWorkbenchComponent {
  readonly fieldTypes: Array<{ label: string; value: ConfiguratorFieldType }> = [
    { label: "Texto", value: "string" },
    { label: "Numero", value: "number" },
    { label: "Data", value: "date" },
    { label: "Selecao", value: "select" },
    { label: "Logico", value: "boolean" },
    { label: "Texto longo", value: "textarea" }
  ];

  readonly gridOptions = [3, 4, 6, 8, 12];

  readonly palette: Array<PaletteItem> = [
    { type: "string", label: "Texto", icon: "an an-textbox" },
    { type: "number", label: "Numero", icon: "an an-hash" },
    { type: "date", label: "Data", icon: "an an-calendar-blank" },
    { type: "select", label: "Selecao", icon: "an an-list" },
    { type: "boolean", label: "Logico", icon: "an an-toggle-left" },
    { type: "textarea", label: "Texto longo", icon: "an an-note-pencil" }
  ];

  screenTitle = "Cadastro configurado";
  screenId = "cadastro-configurado";
  fields: Array<BuilderField> = [
    this.createField({ type: "string", label: "Razao Social", icon: "an an-textbox" }),
    this.createField({ type: "string", label: "CNPJ/CPF", icon: "an an-textbox" }, "cnpjCpf")
  ];
  selectedFieldId = this.fields[0]?.id ?? "";

  get selectedField(): BuilderField | undefined {
    return this.fields.find((field) => field.id === this.selectedFieldId);
  }

  get generatedXml(): string {
    const fieldsXml = this.fields
      .map((field) => {
        const attributes = [
          `property="${this.escapeXml(field.property)}"`,
          `type="${field.type}"`,
          `label="${this.escapeXml(field.label)}"`,
          `required="${field.required}"`,
          `gridMd="${field.gridMd}"`
        ];

        if (field.mask.trim()) {
          attributes.push(`mask="${this.escapeXml(field.mask)}"`);
        }

        if (field.placeholder.trim()) {
          attributes.push(`placeholder="${this.escapeXml(field.placeholder)}"`);
        }

        return `      <field ${attributes.join(" ")} />`;
      })
      .join("\n");

    return [
      `<screen id="${this.escapeXml(this.screenId)}" version="1" type="edit">`,
      `  <meta title="${this.escapeXml(this.screenTitle)}" module="Configurador" />`,
      `  <layout kind="form">`,
      `    <section id="main" title="Principal">`,
      fieldsXml || "      <!-- sem campos -->",
      `    </section>`,
      `  </layout>`,
      `</screen>`
    ].join("\n");
  }

  dropField(event: CdkDragDrop<Array<BuilderField>>) {
    if (event.previousContainer === event.container) {
      moveItemInArray(this.fields, event.previousIndex, event.currentIndex);
      return;
    }

    const paletteItem = this.palette[event.previousIndex];
    const field = this.createField(paletteItem);
    this.fields.splice(event.currentIndex, 0, field);
    this.selectedFieldId = field.id;
  }

  selectField(field: BuilderField) {
    this.selectedFieldId = field.id;
  }

  removeSelectedField() {
    const index = this.fields.findIndex((field) => field.id === this.selectedFieldId);

    if (index < 0) {
      return;
    }

    this.fields.splice(index, 1);
    this.selectedFieldId = this.fields[Math.max(0, index - 1)]?.id ?? "";
  }

  duplicateSelectedField() {
    const selected = this.selectedField;

    if (!selected) {
      return;
    }

    const copy: BuilderField = {
      ...selected,
      id: this.createId(),
      property: `${selected.property}Copy`,
      label: `${selected.label} copia`
    };

    const index = this.fields.findIndex((field) => field.id === selected.id);
    this.fields.splice(index + 1, 0, copy);
    this.selectedFieldId = copy.id;
  }

  updateType(value: ConfiguratorFieldType) {
    if (this.selectedField) {
      this.selectedField.type = value;
    }
  }

  updateGrid(value: number) {
    if (this.selectedField) {
      this.selectedField.gridMd = Number(value);
    }
  }

  private createField(item: PaletteItem, property?: string): BuilderField {
    const normalizedProperty = property ?? this.toPropertyName(item.label);

    return {
      id: this.createId(),
      property: normalizedProperty,
      label: item.label,
      type: item.type,
      required: false,
      mask: "",
      gridMd: 6,
      placeholder: ""
    };
  }

  private toPropertyName(label: string): string {
    const sanitized = label
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-zA-Z0-9]+(.)/g, (_, chr: string) => chr.toUpperCase())
      .replace(/[^a-zA-Z0-9]/g, "");

    return sanitized.charAt(0).toLowerCase() + sanitized.slice(1);
  }

  private createId(): string {
    return `field-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
  }

  private escapeXml(value: string): string {
    return value
      .replace(/&/g, "&amp;")
      .replace(/"/g, "&quot;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }
}
