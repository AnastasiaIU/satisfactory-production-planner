<?php

require_once(__DIR__ . '/../models/ItemModel.php');

class ItemService
{
    private ItemModel $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function isTableEmpty(): bool
    {
        return !$this->itemModel->hasAnyRecords();
    }

    public function loadItemsFromJson(string $jsonPath): void
    {
        $jsonContent = file_get_contents($jsonPath);
        $jsonContent = mb_convert_encoding($jsonContent, 'UTF-8', 'UTF-16LE');
        $jsonContent = trim($jsonContent, "\xEF\xBB\xBF");
        $data = json_decode($jsonContent, true);

        if ($data === null) {
            error_log('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $validNativeClasses = [
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptor'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGResourceDescriptor'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptorBiomass'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptorNuclearFuel'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGPowerShardDescriptor'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGAmmoTypeProjectile'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGAmmoTypeSpreadshot'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGAmmoTypeInstantHit'",
            "/Script/CoreUObject.Class'/Script/FactoryGame.FGItemDescriptorPowerBoosterFuel'"
        ];

        $ficsmas = [
            'Desc_Gift_C',
            'Desc_CandyCane_C',
            'Desc_Snow_C',
            'Desc_XmasBallCluster_C',
            'Desc_XmasStar_C',
            'Desc_XmasWreath_C',
            'Desc_XmasBow_C',
            'Desc_XmasBall1_C',
            'Desc_XmasBall2_C',
            'Desc_XmasBall3_C',
            'Desc_XmasBall4_C',
            'Desc_XmasBranch_C'
        ];

        $raw_resources = [
            'Desc_OreIron_C',
            'Desc_OreCopper_C',
            'Desc_Stone_C',
            'Desc_Coal_C',
            'Desc_Water_C',
            'Desc_PackagedWater_C',
            'Desc_LiquidOil_C',
            'Desc_PackagedOil_C',
            'Desc_OreGold_C',
            'Desc_OreBauxite_C',
            'Desc_RawQuartz_C',
            'Desc_Sulfur_C',
            'Desc_OreUranium_C',
            'Desc_NitrogenGas_C',
            'Desc_PackagedNitrogenGas_C',
            'Desc_SAM_C'
        ];

        $tier_0 = [
            'Desc_IronIngot_C',
            'Desc_IronPlate_C',
            'Desc_IronRod_C',
            'Desc_CopperIngot_C',
            'Desc_Wire_C',
            'Desc_Cable_C',
            'Desc_Cement_C',
            'Desc_IronScrew_C',
            'Desc_IronPlateReinforced_C',
            'Desc_GenericBiomass_C'
        ];

        $tier_2 = [
            'Desc_CopperSheet_C',
            'Desc_Rotor_C',
            'Desc_ModularFrame_C',
            'Desc_SpaceElevatorPart_1_C',
            'Desc_Biofuel_C'
        ];

        $tier_3 = [
            'Desc_SteelIngot_C',
            'Desc_SteelPlate_C',
            'Desc_SteelPipe_C',
            'Desc_SpaceElevatorPart_2_C'
        ];

        $tier_4 = [
            'Desc_SteelPlateReinforced_C',
            'Desc_Stator_C',
            'Desc_Motor_C',
            'Desc_SpaceElevatorPart_3_C'
        ];

        $tier_5 = [
            'Desc_Plastic_C',
            'Desc_Rubber_C',
            'Desc_PolymerResin_C',
            'Desc_PetroleumCoke_C',
            'Desc_CircuitBoard_C',
            'Desc_LiquidFuel_C',
            'Desc_Fuel_C',
            'Desc_HeavyOilResidue_C',
            'Desc_PackagedOilResidue_C',
            'Desc_LiquidBiofuel_C',
            'Desc_PackagedBiofuel_C',
            'Desc_FluidCanister_C'
        ];

        $tier_6 = [
            'Desc_Computer_C',
            'Desc_ModularFrameHeavy_C',
            'Desc_SpaceElevatorPart_4_C',
            'Desc_SpaceElevatorPart_5_C'
        ];

        $tier_7 = [
            'Desc_AluminaSolution_C',
            'Desc_PackagedAlumina_C',
            'Desc_AluminumScrap_C',
            'Desc_AluminumIngot_C',
            'Desc_AluminumPlate_C',
            'Desc_AluminumCasing_C',
            'Desc_ModularFrameLightweight_C',
            'Desc_SulfuricAcid_C',
            'Desc_PackagedSulfuricAcid_C',
            'Desc_Battery_C',
            'Desc_ComputerSuper_C',
            'Desc_SpaceElevatorPart_7_C'
        ];

        $tier_8 = [
            'Desc_UraniumCell_C',
            'Desc_ElectromagneticControlRod_C',
            'Desc_NuclearFuelRod_C',
            'Desc_NuclearWaste_C',
            'Desc_SpaceElevatorPart_6_C',
            'Desc_GasTank_C',
            'Desc_AluminumPlateReinforced_C',
            'Desc_CoolingSystem_C',
            'Desc_ModularFrameFused_C',
            'Desc_MotorLightweight_C',
            'Desc_SpaceElevatorPart_8_C',
            'Desc_NitricAcid_C',
            'Desc_PackagedNitricAcid_C',
            'Desc_NonFissibleUranium_C',
            'Desc_PlutoniumPellet_C',
            'Desc_PlutoniumCell_C',
            'Desc_PlutoniumFuelRod_C',
            'Desc_PlutoniumWaste_C',
            'Desc_CopperDust_C',
            'Desc_PressureConversionCube_C',
            'Desc_SpaceElevatorPart_9_C'
        ];

        $tier_9 = [
            'Desc_Diamond_C',
            'Desc_TimeCrystal_C',
            'Desc_FicsiteIngot_C',
            'Desc_FicsiteMesh_C',
            'Desc_SpaceElevatorPart_10_C',
            'Desc_QuantumEnergy_C',
            'Desc_DarkEnergy_C',
            'Desc_DarkMatter_C',
            'Desc_QuantumOscillator_C',
            'Desc_TemporalProcessor_C',
            'Desc_SpaceElevatorPart_12_C',
            'Desc_SingularityCell_C',
            'Desc_SpaceElevatorPart_11_C',
            'Desc_Ficsonium_C',
            'Desc_FicsoniumFuelRod_C'
        ];

        $mam = [
            'Desc_CrystalShard_C',
            'Desc_AlienProtein_C',
            'Desc_AlienDNACapsule_C',
            'Desc_Fabric_C',
            'Desc_GoldIngot_C',
            'Desc_HighSpeedWire_C',
            'Desc_CircuitBoardHighSpeed_C',
            'Desc_HighSpeedConnector_C',
            'Desc_QuartzCrystal_C',
            'Desc_Silica_C',
            'Desc_CrystalOscillator_C',
            'Desc_DissolvedSilica_C',
            'Desc_Gunpowder_C',
            'Desc_CompactedCoal_C',
            'Desc_LiquidTurboFuel_C',
            'Desc_TurboFuel_C',
            'Desc_GunpowderMK2_C',
            'Desc_RocketFuel_C',
            'Desc_PackagedRocketFuel_C',
            'Desc_IonizedFuel_C',
            'Desc_PackagedIonizedFuel_C',
            'Desc_SAMIngot_C',
            'Desc_SAMFluctuator_C',
            'Desc_AlienPowerFuel_C'
        ];

        $equipment = [
            'Desc_Filter_C',
            'Desc_HazmatFilter_C',
            'Desc_SpikedRebar_C',
            'Desc_Rebar_Stunshot_C',
            'Desc_Rebar_Spreadshot_C',
            'Desc_Rebar_Explosive_C',
            'Desc_NobeliskExplosive_C',
            'Desc_NobeliskGas_C',
            'Desc_NobeliskShockwave_C',
            'Desc_NobeliskCluster_C',
            'Desc_NobeliskNuke_C',
            'Desc_CartridgeStandard_C',
            'Desc_CartridgeSmartProjectile_C',
            'Desc_CartridgeChaos_C'
        ];

        $filteredClasses = [];
        foreach ($data as $class) {
            if (isset($class['NativeClass']) && in_array($class['NativeClass'], $validNativeClasses, true)) {
                $filteredClasses = array_merge($filteredClasses, $class['Classes'] ?? []);

                foreach ($class['Classes'] as $item) {
                    $id = $item['ClassName'] ?? null;

                    if (in_array($id, $ficsmas)) continue;

                    $display_name = $item['mDisplayName'] ?? null;

                    $icon_name = $item['mSmallIcon'] ?? null;
                    $segments = explode('/', $icon_name);
                    $last_segment = end($segments);
                    $parts = explode('.', $last_segment);
                    $icon_name = str_replace('IconDesc_', '', $parts[0]) . '.png';

                    $category = 'Miscellaneous';
                    $display_order = 0;

                    if (in_array($id, $raw_resources)) {
                        $category = 'Raw Resources';
                        $display_order = array_search($id, $raw_resources);
                    }
                    if (in_array($id, $tier_0)) {
                        $category = 'Tier 0';
                        $display_order = array_search($id, $tier_0);
                    }
                    if (in_array($id, $tier_2)) {
                        $category = 'Tier 2';
                        $display_order = array_search($id, $tier_2);
                    }
                    if (in_array($id, $tier_3)) {
                        $category = 'Tier 3';
                        $display_order = array_search($id, $tier_3);
                    }
                    if (in_array($id, $tier_4)) {
                        $category = 'Tier 4';
                        $display_order = array_search($id, $tier_4);
                    }
                    if (in_array($id, $tier_5)) {
                        $category = 'Tier 5';
                        $display_order = array_search($id, $tier_5);
                    }
                    if (in_array($id, $tier_6)) {
                        $category = 'Tier 6';
                        $display_order = array_search($id, $tier_6);
                    }
                    if (in_array($id, $tier_7)) {
                        $category = 'Tier 7';
                        $display_order = array_search($id, $tier_7);
                    }
                    if (in_array($id, $tier_8)) {
                        $category = 'Tier 8';
                        $display_order = array_search($id, $tier_8);
                    }
                    if (in_array($id, $tier_9)) {
                        $category = 'Tier 9';
                        $display_order = array_search($id, $tier_9);
                    }
                    if (in_array($id, $mam)) {
                        $category = 'MAM';
                        $display_order = array_search($id, $mam);
                    }
                    if (in_array($id, $equipment)) {
                        $category = 'Equipment';
                        $display_order = array_search($id, $equipment);
                    }

                    if ($id && $display_name && $icon_name) {
                        $this->itemModel->insert($id, $display_name, $icon_name, $category, $display_order);
                    }
                }
            }
        }
    }
}