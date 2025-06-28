<template>
    <el-dialog
        :visible="isOpen"
        title="Registrar Pago"
        :close-on-click-modal="false"
        width="700px"
        @close="closeModal"
        append-to-body
        center
    >
        <!-- Contenido del modal -->
        <div class="payment-dialog-content">
            <!-- Información del vehículo -->
            <div class="mb-4">
                <div class="row">
                    <!-- Primera fila de información -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted">PLACA</label>
                            <div class="form-control-plaintext">
                                <h5 class="mb-0 fw-bold">
                                    {{ vehiculo ? vehiculo.placa : "" }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted"
                                >NÚMERO INTERNO</label
                            >
                            <div class="form-control-plaintext">
                                <h5 class="mb-0 fw-bold">
                                    {{
                                        vehiculo
                                            ? vehiculo.numero_interno || "1"
                                            : "-"
                                    }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted"
                                >FECHA DE INGRESO</label
                            >
                            <div class="form-control-plaintext">
                                <h5 class="mb-0 fw-bold">
                                    {{
                                        vehiculo
                                            ? formatDate(vehiculo.fecha_ingreso)
                                            : ""
                                    }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted"
                                >MES/AÑO A PAGAR</label
                            >
                            <div class="form-control-plaintext">
                                <h5 class="mb-0 fw-bold">
                                    {{ getMonthName(mes) }} / {{ year }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- La sección de meses seleccionados ha sido eliminada ya que este modal es solo para pagos individuales -->

            <div class="row">
                <!-- Monto a pagar -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label">Monto a pagar:</label>
                        <div class="d-flex align-items-center">
                            <div class="currency-prefix me-2">{{ divisa }}</div>
                            <el-input-number
                                v-model="monto"
                                :precision="2"
                                :step="0.01"
                                :min="0"
                                controls-position="right"
                                class="flex-grow-1"
                            ></el-input-number>
                        </div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label">Fecha:</label>
                        <el-date-picker
                            v-model="fecha"
                            type="date"
                            placeholder="Seleccione fecha"
                            format="dd/MM/yyyy"
                            value-format="yyyy-MM-dd"
                            style="width: 100%"
                        ></el-date-picker>
                    </div>
                </div>

                <!-- Selección de color -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label">Seleccionar color:</label>
                        <div class="color-selector">
                            <div class="d-flex flex-column">
                                <el-color-picker
                                    v-model="selectedColor"
                                    class="mb-2"
                                    :show-alpha="false"
                                    format="hex"
                                ></el-color-picker>
                                <div
                                    class="color-preview mt-2"
                                    :style="{ backgroundColor: selectedColor }"
                                ></div>
                                <small class="text-muted mt-2 d-block">
                                    Color seleccionado: {{ selectedColor }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección para mostrar el tipo y monto de descuento aplicado -->
            <div v-if="descuento > 0" class="mb-4">
                <div class="card bg-light border-success">
                    <div class="card-body">
                        <h6 class="card-title text-success mb-2">
                            <i class="fas fa-tag me-2"></i> DESCUENTO APLICADO
                        </h6>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="card-text mb-0">
                                    <strong>Tipo de descuento:</strong>
                                    {{ getTipoDescuento() }}
                                </p>
                                <small class="text-muted">
                                    {{ getDescripcionDescuento() }}
                                </small>
                            </div>
                            <div class="text-success">
                                <h5 class="mb-0 fw-bold">
                                    - {{ divisa }} {{ descuento.toFixed(2) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="closeModal">Cancelar</el-button>
                <el-button
                    type="primary"
                    @click="savePago"
                    :loading="isSubmitting"
                >
                    Guardar
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
export default {
    name: "RegisterPaymentModal",
    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
        vehiculo: {
            type: Object,
            default: null
        },
        year: {
            type: Number,
            default: null
        },
        mes: {
            type: [String, Number],
            default: null
        }
    },
    data() {
        return {
            monto: 0,
            fecha: this.getCurrentDate(),
            selectedColor: "#34d399", // Verde por defecto en formato hexadecimal
            isSubmitting: false,
            descuento: 0, // Para aplicar descuentos individuales
            monthNames: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ]
        };
    },
    mounted() {
        // Al montar el componente, calculamos los valores según la lógica de negocio
        if (this.isOpen && this.vehiculo) {
            this.calcularSaldoYColor();
        }
    },
    beforeDestroy() {
        // Limpiar los listeners para evitar memory leaks
        this.$root.$off(
            "init-payment-modal",
            this.initializeWithCalculatedData
        );
    },
    watch: {
        isOpen(value) {
            if (value) {
                // Al abrir el modal, calculamos valores según la lógica de negocio
                this.calcularSaldoYColor();
                this.fecha = this.getCurrentDate();
            }
        },
        selectedColor(newVal) {
            // Nos aseguramos que el valor siempre sea hexadecimal
            if (newVal && !newVal.startsWith("#")) {
                // Si por alguna razón no es hexadecimal, lo convertimos
                this.selectedColor = this.convertToHex(newVal);
            }
        },
        // Observar cambios en la fecha para recalcular descuentos
        fecha() {
            this.calcularSaldoYColor();
        }
    },
    computed: {
        // Las propiedades computadas no relacionadas con múltiples meses se mantienen
        divisa() {
            // Obtener la divisa del plan del vehículo si está disponible
            if (
                this.vehiculo &&
                this.vehiculo.subscription &&
                this.vehiculo.subscription.plan &&
                this.vehiculo.subscription.plan.currency
            ) {
                return this.vehiculo.subscription.plan.currency;
            }
            // Si no está disponible, devolver valor por defecto
            return "PEN";
        }
    },
    methods: {
        getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, "0");
            const day = String(today.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        },
        /**
         * Calcula el saldo y color según la lógica de negocio para pagos individuales
         * Esta función sigue exactamente la misma lógica que el código PHP original
         */ calcularSaldoYColor() {
            // Verificar que el vehículo tenga suscripción y plan
            if (
                !this.vehiculo ||
                !this.vehiculo.subscription ||
                !this.vehiculo.subscription.plan
            ) {
                this.monto = 25.0;
                this.selectedColor = "#34d399"; // Verde por defecto
                this.descuento = 0;
                return;
            }

            const subscription = this.vehiculo.subscription;
            const plan = subscription.plan;

            // Establecer valores iniciales
            let monto = plan.price || 0;
            let color = "#34d399"; // Color verde por defecto
            this.descuento = 0;

            // Lógica para pagos individuales - Siguiendo exactamente el código PHP
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1;

            // Verificar si el pago es para el mes actual
            if (this.year === currentYear && this.mes === currentMonth) {
                // Verificar si estamos en los primeros 5 días del mes
                if (currentDate.getDate() <= 5) {
                    // Buscar descuento por inicio de mes
                    const discountInicioMes =
                        plan.discounts &&
                        plan.discounts.find(
                            d => d.slug === "descuento-inicio-mes"
                        );

                    if (discountInicioMes) {
                        this.descuento = discountInicioMes.value || 0;
                        monto = plan.price - this.descuento;
                        color = "#34d399"; // Verde para descuento
                    } else {
                        monto = plan.price;
                        color = "#34d399"; // Verde normal
                    }
                } else {
                    // Después del día 5, precio normal
                    monto = plan.price;
                    color = "#34d399"; // Verde normal
                }
            }
            // Verificar si el pago es para un mes futuro
            else if (
                this.year > currentYear ||
                (this.year === currentYear && this.mes > currentMonth)
            ) {
                // Aplicar descuento para meses futuros (mismo que inicio de mes)
                const discountInicioMes =
                    plan.discounts &&
                    plan.discounts.find(d => d.slug === "descuento-inicio-mes");

                if (discountInicioMes) {
                    this.descuento = discountInicioMes.value || 0;
                    monto = plan.price - this.descuento;
                    color = "#34d399"; // Verde para descuento
                } else {
                    monto = plan.price;
                    color = "#34d399"; // Verde normal
                }
            }
            // Para meses pasados, precio normal sin descuento
            else {
                monto = plan.price;
                color = "#dc2626"; // Rojo para pagos vencidos
            }

            // Establecer el monto final y el color
            this.monto = monto;
            this.selectedColor = color;
        },
        getDefaultMonto() {
            // Ahora este método usa la lógica de calcularSaldoYColor
            this.calcularSaldoYColor();
            return this.monto;
        },
        formatDate(date) {
            if (!date) return "";
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, "0");
            const month = String(d.getMonth() + 1).padStart(2, "0");
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },
        getMonthName(mes) {
            // Convierte el número del mes (1-12) a su nombre
            if (!mes) return "";

            // Si es numérico, devolver el nombre del mes del array
            if (!isNaN(mes)) {
                return this.monthNames[parseInt(mes) - 1];
            }

            // Si ya es una cadena (nombre del mes), devolverlo directamente
            return month;
        },
        closeModal() {
            this.$emit("close");
        },
        /**
         * Inicializa el modal con los datos calculados desde el componente padre
         * (Lo mantenemos para compatibilidad, pero ahora el modal es autónomo)
         * @param {Object} data - Datos calculados
         */
        initializeWithCalculatedData(data) {
            console.log("Inicialización del modal con datos calculados:", data);
            if (data) {
                if (data.monto !== undefined) this.monto = data.monto;
                if (data.color) this.selectedColor = data.color;
                if (data.descuento !== undefined)
                    this.descuento = data.descuento;
            }
        },
        savePago() {
            // Validar que los montos sean correctos antes de proceder
            if (!this.monto || this.monto <= 0) {
                this.$message.error("El monto debe ser mayor a 0");
                return;
            }

            // Verificar que el monto no exceda lo permitido por el plan
            if (
                this.vehiculo &&
                this.vehiculo.subscription &&
                this.vehiculo.subscription.plan
            ) {
                const planPrice = this.vehiculo.subscription.plan.price || 25.0;
                const maxPermitido = planPrice * 1.5; // Permitimos hasta un 50% más

                if (this.monto > maxPermitido) {
                    this.$message.error(
                        `El monto máximo permitido es ${
                            this.divisa
                        } ${maxPermitido.toFixed(2)}`
                    );
                    return;
                }
            }

            this.isSubmitting = true; // Crear objeto de pago
            const pago = {
                vehiculoId: this.vehiculo ? this.vehiculo.id : null,
                year: this.year,
                mes: this.mes,
                monto: parseFloat(this.monto),
                fecha: this.fecha,
                color: this.selectedColor,
                tipo: this.vehiculo.subscription.plan.type,
                descuento: parseFloat(this.descuento) || 0,
                moneda: this.divisa
            };

            this.isSubmitting = false;
            this.$emit("save", pago);
            this.closeModal();
        },

        /**
         * Convierte cualquier formato de color a hexadecimal
         */
        convertToHex(color) {
            // Si ya es hexadecimal, lo devolvemos
            if (color.startsWith("#")) {
                return color;
            }

            // Si es RGB o RGBA, convertimos a hex
            if (color.startsWith("rgb")) {
                const rgbMatch = color.match(
                    /rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/
                );
                if (rgbMatch) {
                    const r = parseInt(rgbMatch[1]);
                    const g = parseInt(rgbMatch[2]);
                    const b = parseInt(rgbMatch[3]);

                    const toHex = c => {
                        const hex = c.toString(16);
                        return hex.length === 1 ? "0" + hex : hex;
                    };

                    return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
                }
            }

            // Si no pudimos convertirlo, devolvemos un color por defecto
            return "#34d399";
        },

        /**
         * Obtiene el tipo de descuento aplicado basado en la lógica de negocio
         * @returns {string} Nombre del tipo de descuento
         */
        getTipoDescuento() {
            if (this.descuento <= 0) return "Sin descuento";

            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1;

            // Es un mes futuro
            if (
                this.year > currentYear ||
                (this.year === currentYear && this.month > currentMonth)
            ) {
                return "Descuento por pago anticipado";
            }

            // Es el mes actual y estamos en los primeros 5 días
            if (
                this.year === currentYear &&
                this.month === currentMonth &&
                currentDate.getDate() <= 5
            ) {
                return "Descuento por pago puntual";
            }

            return "Descuento aplicado";
        },

        /**
         * Obtiene una descripción detallada del descuento
         * @returns {string} Descripción del descuento
         */
        getDescripcionDescuento() {
            if (this.descuento <= 0) return "";

            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1;

            // Es un mes futuro
            if (
                this.year > currentYear ||
                (this.year === currentYear && this.month > currentMonth)
            ) {
                return "Se aplica descuento por pago anticipado de mes futuro";
            }

            // Es el mes actual y estamos en los primeros 5 días
            if (
                this.year === currentYear &&
                this.month === currentMonth &&
                currentDate.getDate() <= 5
            ) {
                return "Se aplica descuento por pago en los primeros 5 días del mes";
            }

            return "Descuento especial aplicado al plan seleccionado";
        }
    }
};
</script>

<style scoped>
/* Estilos para el modal de pago */
.payment-dialog-content {
    max-width: 100%;
}

.form-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.form-control-plaintext {
    padding: 0.375rem 0;
}

.color-selector {
    position: relative;
    width: 100%;
}

.color-preview {
    width: 100%;
    height: 30px;
    border-radius: 4px;
    border: 1px solid #dcdfe6;
}

.currency-prefix {
    font-weight: 500;
    font-size: 1rem;
    color: #606266;
    min-width: 30px;
    text-align: center;
}

/* Estilo para tarjetas */
.card-title {
    font-weight: 600;
}

.border-success {
    border-width: 1px;
    border-color: #10b981 !important;
}

.card-body {
    padding: 1rem;
}

/* Estilo para los campos de formulario */
.form-control,
.input-group-text {
    border-radius: 4px;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

/* Mejoras para los componentes de Element UI */
:deep(.el-input-number) {
    width: 100%;
}

:deep(.el-input-number .el-input__inner) {
    text-align: left;
}

:deep(.el-date-editor.el-input) {
    width: 100%;
}

:deep(.el-color-picker) {
    width: auto;
    display: block;
    text-align: center;
}

/* Estilo para el pie del diálogo */
.dialog-footer {
    display: flex;
    justify-content: flex-end;
}

/* Mejora en la visualización del diálogo */
:deep(.el-dialog) {
    border-radius: 8px;
}

:deep(.el-dialog__header) {
    border-bottom: 1px solid #e9ecef;
    padding: 15px 20px;
}

:deep(.el-dialog__body) {
    padding: 20px;
}

:deep(.el-dialog__title) {
    font-weight: bold;
    font-size: 1.2rem;
}

:deep(.el-dialog__footer) {
    border-top: 1px solid #e9ecef;
    padding: 15px 20px;
}
</style>
