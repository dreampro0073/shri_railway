import { Text, Modal, TouchableOpacity, Alert, StyleSheet, View, KeyboardAvoidingView, ScrollView, PermissionsAndroid, Platform, TextInput } from 'react-native';
import React, { Component } from 'react';
import CustomHeader from '../../components/CustomHeader';
import FormItem from '../../components/FormItem';
import Theme from '../../Theme';
import EditInput from '../../components/EditInput';
import CustomButton from '../../components/CustomButton';
import NetworkUtils from '../../mics/NetworkUtils';
import { connect } from 'react-redux';
import { resetData } from '../Dashboard/acions';
import Icon from 'react-native-vector-icons/MaterialIcons';
import BottomModal from '../../components/BottomModal';

import {
	BLEPrinter
} from "react-native-thermal-receipt-printer";
import { config } from '../../config';
const { canteen_id } = config.user;


class AddDailyEntry extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loading: false,
			modal_visible: false,
			entry_id: 0,
			unique_id: 0,
			billing_date: "",
			canteen_items: [],
			customer: {
				name: '',
				canteen_id: this.props.canteen_id,
				mobile: '',
				pay_type: 1,
				products: [],
				total_amount: 0,
			},
			total_amount: 0,
			added_product: {
				id: 0,
				item_name: '',
				price: 0,
				quantity: 0,
			},
			printers: [],
			printer: {},
			final_items: [],
			searh_text: '',

			modal_visible_print: false,
		}
	}

	componentDidMount = () => {

		if (this.props.route.params.entry_id) {
			this.setState({ entry_id: this.props.route.params.entry_id }, () => {
				this._getEditEntry();

			});
		}
		this._getCanteenItmes();



		this.requestCameraPermission();
		this.requestCameraPermission2();

	}

	requestCameraPermission = async () => {
		try {
			const granted = await PermissionsAndroid.request(
				PermissionsAndroid.PERMISSIONS.BLUETOOTH_SCAN,
				{
					title: 'Cool Photo App Camera Permission',
					message:
						'Cool Photo App needs access to your camera ' +
						'so you can take awesome pictures.',
					buttonNeutral: 'Ask Me Later',
					buttonNegative: 'Cancel',
					buttonPositive: 'OK',
				},
			);
			if (granted === PermissionsAndroid.RESULTS.GRANTED) {
				console.log('You can use the camera');
			} else {
				console.log('Camera permission denied');
			}
		} catch (err) {
			console.warn(err);
		}
	};

	requestCameraPermission2 = async () => {
		try {
			const granted = await PermissionsAndroid.request(
				PermissionsAndroid.PERMISSIONS.BLUETOOTH_CONNECT,
				{
					title: 'Cool Photo App bl;uetooth Permission',
					message:
						'Cool Photo App needs access to your camera ' +
						'so you can take awesome pictures.',
					buttonNeutral: 'Ask Me Later',
					buttonNegative: 'Cancel',
					buttonPositive: 'OK',
				},
			);
			if (granted === PermissionsAndroid.RESULTS.GRANTED) {
				console.log('You can use the camera');
			} else {
				console.log('Camera permission denied');
			}
		} catch (err) {
			console.warn(err);
		}
	};

	_bluetooth = () => {

		BLEPrinter.init().then(() => {
			BLEPrinter.getDeviceList().then((printers) => {
				this.setState({ printers: printers, modal_visible_print: true });
			});
		});
	}
	setPrinter = (printer) => {
		BLEPrinter.connectPrinter(printer.inner_mac_address).then((printer) => {
			this.setState({ printer: printer, modal_visible_print: false });
			this.props.resetData('printer', printer, () => { });
		})
	}
	printTextTest = () => {
		// console.log("Asdasd");
		const { name, mobile, products, unique_id, show_date, created_time } = this.state.customer;
		const { total_amount, billing_date } = this.state;

		// const leftAlign = '\x1B' + 'a' + '3'; 

		// let str = '';
		// let str1 = `
		// 	${leftAlign}Canteen Master

		// 	${leftAlign}Date: ${billing_date}
		// 	${leftAlign}Name: ${name}
		// 	${leftAlign}Mobile: ${mobile}
		// 	${leftAlign}------------------------------
		// 	${leftAlign}Item   Qty   Price   Amount   
		// 	${leftAlign}------------------------------
		// `;

		// let str2 = '';
		// for (let index = 0; index < products.length; index++) {
		// 	const element = products[index];

		// 	str2 += `
		// 		${leftAlign}${element.item_name}   ${element.quantity}    ${element.price}     ${element.paid_amount}
		// 	`;

		// 	BluetoothEscposPrinter.printColumn([16,16],[BluetoothEscposPrinter.ALIGN.LEFT,BluetoothEscposPrinter.ALIGN.RIGHT],["Date",'64000'],{});
		// }

		// let str3 =`	
		// 	${leftAlign}------------------------------
		// 	${leftAlign}Total           Rs ${total_amount}
		// 	${leftAlign}------------------------------
		// `;

		// str = str.concat(str1,str2,str3);

		let str = "<C>\n</C><C>AC Executive Lounge</C>\n<C>Railway Station PF-1</C>\n<C>";
		str += "Haridwar</C>\n<C>";

		str += "--------------------------------" + "</C>\n<C>";

		if (name) {
			str += "Name : " + name + "</C>\n<C>";
		}
		if (mobile) {
			str += "Mobile : " + mobile + "</C>\n<C>";
		}
		if (unique_id) {
			str += "Bill No : " + unique_id + "</C>\n<C>";
		}
		if (show_date) {
			str += "Date : " + show_date + ',' + created_time + "</C>\n<C>";
		}
		str += "--------------------------------" + "</C>\n<C>";
		str += "Products</C>\n<C>";
		str += "--------------------------------" + "</C>\n<C>";
		for (let index = 0; index < products.length; index++) {
			const element = products[index];

			str += element.item_name + " : " + element.quantity + " x " + element.price + " = Rs " + element.paid_amount + "</C>\n<C>";

		}

		str += "--------------------------------" + "</C>\n<C>";
		str += "Total Amount: Rs " + total_amount + "</C>\n<C>";
		str += "--------------------------------" + "</C>\n<C>";

		BLEPrinter.printText(str);
	}
	_getEditEntry = () => {
		this.setState({ loading: true });
		NetworkUtils.postData(
			'/daily-entries/edit-init',
			{ canteen_id: this.props.canteen_id, entry_id: this.state.entry_id },

			(data) => {
				// console.log("itedm data", data);
				this.setState({
					customer: data.s_entry,
					loading: false,
					total_amount: data.s_entry.total_amount

				});
			},
			(message) => {
				Alert.alert('Warning!', message);
				this.setState({ loading: false })
			}
		)
	}
	_getCanteenItmes = () => {
		this.setState({ loading: true });
		NetworkUtils.postData(
			'/canteen-items/drop-list',
			{ canteen_id: this.props.canteen_id, entry_id: this.state.entry_id },
			// {canteen_id: canteen_id},
			(data) => {
				// console.log("itedm data", data);
				this.setState({
					canteen_items: data.canteen_items,
					final_items: data.canteen_items,
					loading: false,

				});
			},
			(message) => {
				Alert.alert('Warning!', message);
				this.setState({ loading: false })
			}
		)
	}

	_onChangeText = (prop, value) => {
		if (value != 'undefined') {
			this.setState({ customer: { ...this.state.customer, [prop]: value } });
		}

		console.log("item ki value : ", value);
	}

	_storeDailyEntery = () => {
		this.setState({ customer: { ...this.state.customer, total_amount: this.state.total_amount }, processing: true }, () => {
			this._storeProductDetail()
		})
	}

	_storeProductDetail = () => {
		let data = this.state.customer;
		NetworkUtils.postData(
			'/daily-entries/store',
			data,
			(data) => {
				this.props.resetData('refresh_screen_id', Date.now(), () => {
					this.setState({ processing: false, entry_id: data.entry_id }, () => {
						this._getEditEntry();
					});
				});
				Alert.alert('Success', data.message, [
					{ text: 'OK', onPress: () => this._refreshBack() },
				]);
			},
			(message) => {
				Alert.alert('Alert!', message);
				this.setState({ processing: false })
			},
		);
	}

	_addNewProduct = () => {
		this.setState({ modal_visible: true });
	}

	_refreshBack = () => {
		return
		this.props.navigation.goBack();
		// this.props.route.params.onGoBack();
	}

	_selectedItem = (my_item) => {

		// console.log(my_item);

		var total_amount = this.state.total_amount;
		let products = this.state.customer.products;
		var index = products.findIndex((obj) => obj.canteen_item_id === my_item.canteen_item_id);

		if (index == -1) {
			total_amount += my_item.paid_amount;
			products.push(my_item);
			this.setState({ final_items: this.state.canteen_items, searh_text: '' });
		} else {
			// products.splice(index,1);
			Alert.alert('Alet', 'You are already selected this Item, Kinly select The quantity.')
		}
		this.setState({ customer: { ...this.state.customer, products: products }, total_amount: total_amount, canteen_id: my_item.canteen_id }, () => {
			this.setState({ modal_visible: false });
		});
	}

	_qutCount = (item, type) => {
		var total_amount = 0;
		let products = this.state.customer.products;

		for (let i = 0; i < products.length; i++) {
			// let stock = products[i].stock;
			let product = products[i];
			if (product.canteen_item_id == item.canteen_item_id) {
				if (type == -1) {
					if (product.quantity > 1) {
						product.quantity = (product.quantity - 1);
					}
				} else {
					product.quantity = (product.quantity + 1);
				}

				product.paid_amount = product.quantity * product.price;
			}

			total_amount += product.paid_amount;
		}

		this.setState({ customer: { ...this.state.customer, products: products }, total_amount: total_amount });
	}

	_removePro = (my_item) => {
		// console.log(my_item);

		var total_amount = this.state.total_amount;

		let products = this.state.customer.products;
		var index = products.findIndex((obj) => obj.canteen_item_id === my_item.canteen_item_id);

		products.splice(index, 1);

		total_amount = total_amount - my_item.paid_amount;

		this.setState({ customer: { ...this.state.customer, products: products }, total_amount: total_amount, canteen_id: my_item.canteen_id }, () => {
			this.setState({ modal_visible: false });
		});
	}

	_addMore = () => {
		this.setState({ customer: { ...this.state.customer, products: [], name: '', mobile: '' }, total_amount: 0, entry_id: 0 });
	}

	_onSearch = (item_name) => {
		this.setState({ searh_text: item_name }, () => {
			const result = this.state.canteen_items.filter((obj) => JSON.stringify(obj).toLowerCase().includes(item_name.toString().toLowerCase()));
			// console.log(result);
			this.setState({ final_items: result });
		});
	}

	_renderProductAddModal = () => {
		const { id, item_name, price } = this.state.added_product;
		const { canteen_items, final_items, searh_text } = this.state;
		return (
			<Modal
				animationType="slide"
				transparent={true}
				visible={this.state.modal_visible}
				onRequestClose={() => this.setState({ modal_visible: false })}
			>
				<View style={styles.modalBox}>
					<View style={{ flex: 1 }}>
						<TouchableOpacity style={{ flex: 1 }} onPress={() => this.setState({ modal_visible: false })} />
					</View>
					<View style={{ flex: 7, backgroundColor: '#fff', paddingTop: 20, borderTopRightRadius: 8, borderTopLeftRadius: 8 }}>
						<View style={[{ flex: 1, paddingHorizontal: 16 }, Theme.top10]}>
							<Text style={[Theme.size14, Theme.BlackBold, Theme.bot5]}>Add Item in List</Text>
							<EditInput placeholder="Search Item" onChangeText={(text) => this._onSearch(text)} value={searh_text} />
							<ScrollView showsVerticalScrollIndicator={false} style={[{ flex: 1 }]}>
								{final_items.map((item, index) => (
									<TouchableOpacity onPress={() => this._selectedItem(item)} key={index} style={[styles.itemBox, id == item.canteen_item_id ? { backgroundColor: Theme.PrimaryColor } : { backgroundColor: Theme.WhiteColor }]}>
										<Text style={[Theme.size16, id == item.canteen_item_id ? Theme.White : Theme.Black]}>{item.item_name} {item.price}</Text>
									</TouchableOpacity>
								))}

							</ScrollView>
						</View>
					</View>
				</View>
			</Modal>

		)
	}

	myFormComponent = () => {
		const { name, mobile, pay_type } = this.state.customer;
		return (
			<View style={{ flex: 1 }}>
				{/* <Text style={[Theme.size18,Theme.Dark,Theme.top5]} >Customer Details</Text> */}
				<FormItem title="Name" >
					<EditInput placeholder="Enter Customer's name " onChangeText={(text) => this._onChangeText('name', text)} value={name} />
				</FormItem>
				<FormItem title="Mobile" style={[Theme.top15]} >
					<EditInput placeholder="Enter mobile number" keyboardType="numeric" maxLength={10} onChangeText={(text) => this._onChangeText('mobile', text)} value={mobile} />
				</FormItem>
				<FormItem title="Category">
					<View style={[Theme.row,]}>
						<TouchableOpacity onPress={() => this._onChangeText('pay_type', 1)} style={[Theme.row, { paddingTop: 5, paddingHorizontal: 5 }]}>
							<Text style={[Theme.size14, Theme.Black]}>UPI</Text>
							<Icon name={pay_type == 1 ? "radio-button-on" : "radio-button-off"} style={{ marginLeft: 12 }} size={20} color={pay_type == 1 ? Theme.PrimaryColor : Theme.DarkColor} />
						</TouchableOpacity>
						<TouchableOpacity onPress={() => this._onChangeText('pay_type', 2)} style={[Theme.row, { paddingTop: 5, paddingHorizontal: 5 }]}>
							<Text style={[Theme.size14, Theme.Black]}>Cash</Text>
							<Icon name={pay_type == 2 ? "radio-button-on" : "radio-button-off"} style={{ marginLeft: 12 }} size={20} color={pay_type == 2 ? Theme.PrimaryColor : Theme.DarkColor} />
						</TouchableOpacity>
					</View>
				</FormItem>
			</View>
		);
	}

	render() {
		const { products } = this.state.customer;
		return (
			<View style={{ flex: 1 }}>
				<CustomHeader title="Add Products" navigation={this.props.navigation} />
				<KeyboardAvoidingView behavior={Platform.OS === "ios" ? "padding" : null} keyboardVerticalOffset={Platform.OS === "ios" ? 64 : 0} style={{ flex: 1 }} >
					<View style={[{ paddingHorizontal: 10 }, Theme.row, Theme.top10]}>
						<View>
							<Text style={[Theme.BlackBold, Theme.size12]}>
								{this.props.printer.device_name}
							</Text>
						</View>
						<View style={{ marginLeft: 'auto' }}>
							<View style={{ width: 110 }}>
								<TouchableOpacity onPress={() => this._bluetooth()} style={{ backgroundColor: Theme.PrimaryColor, borderRadius: 4, paddingHorizontal: 10, paddingVertical: 6 }}>
									<Text style={[Theme.White, Theme.size12, { textAlign: 'center' }]}>Scan Printer</Text>
								</TouchableOpacity>
							</View>
						</View>
					</View>
					<ScrollView showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled" style={{ flex: 1 }}>
						<View style={[{ flex: 1, paddingHorizontal: 10 }, Theme.top10]}>
							{this.myFormComponent()}
							<View style={[Theme.row, Theme.top10, Theme.bot5, { justifyContent: 'space-between' }]}>
								<Text style={[Theme.size18, Theme.Dark]}>Product's Detail</Text>
								<TouchableOpacity onPress={this._addNewProduct} style={[styles.btnSmall, { borderColor: Theme.PrimaryColor }]}>
									<Icon name='add-shopping-cart' color={Theme.PrimaryColor} size={24} />
								</TouchableOpacity>
							</View>
							<View style={[Theme.row, Theme.top10, { padding: 16, paddingVertical: 8, backgroundColor: Theme.PrimaryColor }]}>
								<View style={[Theme.row, { flex: 1 }]}>
									<Text style={[{ flex: 0.6 }, Theme.WhiteBold, Theme.size12]}>Products</Text>
									<Text style={[{ flex: 0.4, textAlign: 'center' }, Theme.WhiteBold, Theme.size12]}>Qut</Text>
								</View>
								<Text style={[Theme.WhiteBold, Theme.size12]}>Rs</Text>
							</View>
							<View style={[styles.boxStyle, Theme.BoxShadow]}>
								{products.map((product, index) => {
									return (
										<View key={index} style={[Theme.row, { padding: 8 }, index % 2 == 0 && { backgroundColor: '#EEE' }]}>
											<View style={[Theme.row, { flex: 1 }]}>
												<Text style={[{ flex: 0.6 }, Theme.BlackMedium, Theme.size12]}>{product.item_name}</Text>
												<View style={[Theme.row, { flex: 0.4, justifyContent: 'flex-end', marginRight: 8 }]}>
													{this.state.entry_id == 0 &&
														<TouchableOpacity onPress={() => this._qutCount(product, -1)} style={[styles.addBtnStyle, { backgroundColor: Theme.RedColor }]}>
															<Text style={[Theme.size16, Theme.WhiteBold]}>-</Text>
														</TouchableOpacity>
													}

													<Text style={[Theme.Primary, Theme.size18, { paddingHorizontal: 4, width: 30 }]}> {product.quantity} </Text>
													{this.state.entry_id == 0 &&
														<TouchableOpacity onPress={() => this._qutCount(product, 1)} style={[styles.addBtnStyle, { backgroundColor: Theme.GreenColor }]}>
															<Text style={[Theme.size16, Theme.WhiteBold]}>+</Text>
														</TouchableOpacity>
													}

												</View>
											</View>
											<View style={[{ minWidth: 32 }]}>
												<Text style={[{ textAlign: 'right' }, Theme.Black, Theme.size12]}>{product.paid_amount}</Text>
												<TouchableOpacity onPress={() => this._removePro(product)} style={[styles.addBtnStyle, { backgroundColor: Theme.GreenColor, position: 'absolute', top: -20, right: -15 }]}>
													<Icon name='close' size={10} color={Theme.WhiteColor} ></Icon>
												</TouchableOpacity>
											</View>
										</View>
									);
								})}
								<View style={[Theme.row, styles.BorderTop, { justifyContent: 'space-between' }]}>
									<Text style={[Theme.DarkGrey, Theme.size14]}>Total Amount -</Text>
									<Text style={[Theme.Black, Theme.size14, { flex: 1, textAlign: 'right' }]}>Rs. {this.state.total_amount}</Text>
								</View>
							</View>
						</View>
					</ScrollView>
					{this._renderProductAddModal()}
					<BottomModal title="Add printer" visible={this.state.modal_visible_print} onPress={() => this.setState({ modal_visible_print: false })} >
						{this.state.printers.map((item, index) => {
							return (
								<View key={index} style={Theme.top10}>
									<TouchableOpacity onPress={() => this.setPrinter(item)} style={[{ paddingVertical: 12, borderRadius: 4 }, item.device_name == this.props.printer.device_name ? { backgroundColor: Theme.PrimaryColor } : { backgroundColor: '#eee' }]}>
										<Text style={[Theme.size12, item.device_name == this.props.printer.device_name ? Theme.WhiteBold : Theme.BlackBold, { textAlign: 'center' }]}>{item.device_name}</Text>
									</TouchableOpacity>
								</View>
							)
						})}
					</BottomModal>
				</KeyboardAvoidingView>
				<View style={{ padding: 16, flexDirection: 'row', marginHorizontal: -10 }}>
					{this.state.entry_id !== 0 ?
						<View style={{ flex: 1, paddingHorizontal: 10 }}>
							<CustomButton title="Add More" onPress={() => this._addMore()} processing={this.state.processing} />
							<CustomButton title="Print" onPress={() => this.printTextTest()} processing={this.state.processing} />

						</View>
						:
						<View style={{ flex: 1, paddingHorizontal: 10 }}>

							<CustomButton title="Submit Detail" onPress={this._storeDailyEntery} processing={this.state.processing} />
						</View>
					}
				</View>
			</View>
		)
	}
}

const styles = StyleSheet.create({
	btnSmall: {
		padding: 4,
		paddingHorizontal: 8,
		marginLeft: 24,
		borderWidth: 0.4,
		borderRadius: 4,
		borderColor: Theme.DarkColor,
	},
	modalContainer: {
		flex: 1,
		backgroundColor: '#fff',
		borderRadius: 8,
		marginHorizontal: 2,
		paddingHorizontal: 8,
	},
	BorderTop: {
		paddingVertical: 8,
		borderTopColor: Theme.GreyColor,
		borderTopWidth: 1,
		paddingHorizontal: 8
	},
	modalBox: {
		flex: 1,
		paddingTop: 32,
		backgroundColor: 'rgba(0,0,0,0.6)'
	},
	itemBox: {
		padding: 12,
		borderWidth: 0.4,
		borderColor: "#CCC",
		borderRadius: 4,
		marginTop: 8,
	},
	boxStyle: {
		backgroundColor: '#fff',
		borderRadius: 4,
	},
	addBtnStyle: {
		width: 24,
		height: 24,
		borderRadius: 20,
		alignItems: 'center',
		justifyContent: 'center',
	}
})

const mapStateToProps = (state) => {
	const { canteen_id, printer } = state.user;
	return { canteen_id, printer };
}
export default connect(mapStateToProps, { resetData })(AddDailyEntry);